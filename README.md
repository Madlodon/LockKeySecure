# LockKeySecure — Documentation technique

Gestionnaire de mots de passe sécurisé développé avec Laravel 13 et PHP 8.4.
Déployé sur AWS EC2 à l'adresse https://laniproject.dev

---

## Stack technique

| Couche | Technologie |
|---|---|
| Backend | Laravel 13, PHP 8.4 |
| Frontend | Blade, Vite, Tailwind CSS |
| Base de données | SQLite (fichier local sur le serveur) |
| Serveur | AWS EC2 Ubuntu 24.04, Nginx, PHP-FPM |
| HTTPS | Certbot (Let's Encrypt — certificat gratuit, renouvellement automatique) |
| Authentification OAuth | Laravel Socialite |
| 2FA | pragmarx/google2fa-laravel (TOTP) |
| QR Code | bacon/bacon-qr-code |

---

## Authentification

### 1. Connexion classique (email + mot de passe)

Le mot de passe de connexion est **hashé avec bcrypt** via Laravel :

```php
// User.php — cast automatique
'password' => 'hashed'
```

Le hash bcrypt est **irréversible** — Laravel compare le mot de passe soumis au hash stocké sans jamais décoder. `BCRYPT_ROUNDS=12` dans le `.env` contrôle le coût computationnel (plus haut = plus lent à brute-forcer).

### 2. OAuth (Google, GitHub, Discord)

Géré par **Laravel Socialite** (`OAuthController.php`).

Flux :
1. L'utilisateur clique "Continuer avec Google/GitHub/Discord"
2. Redirection vers le provider OAuth
3. Le provider redirige vers `/auth/{provider}/callback` avec un code
4. Socialite échange le code contre un token et récupère le profil utilisateur
5. `User::updateOrCreate()` crée ou met à jour l'utilisateur en BD selon `oauth_provider_id`
6. L'utilisateur est connecté automatiquement (`Auth::login`)

Les credentials OAuth (Client ID, Secret) sont stockés dans `.env` et jamais en BD.

Providers supportés : `google`, `github`, `discord`

### 3. Passkey (WebAuthn / empreinte digitale)

Implémentation WebAuthn manuelle sans librairie externe (`PasskeyController.php`).

**Enregistrement d'un passkey (depuis les paramètres) :**
1. `POST /passkeys/options` — le serveur génère un challenge aléatoire de 32 bytes (base64url), le stocke en session
2. Le navigateur appelle `navigator.credentials.create()` avec le challenge
3. L'appareil utilise Touch ID / Windows Hello / clé physique pour signer
4. `POST /passkeys` — le serveur vérifie : challenge ✓, origin ✓, type `webauthn.create` ✓
5. Le `credential_id` et la réponse d'attestation sont stockés en BD (table `passkeys`)

**Connexion avec passkey :**
1. `POST /passkeys/auth/options` — nouveau challenge généré, stocké en session
2. Le navigateur appelle `navigator.credentials.get()` — l'appareil demande l'empreinte
3. `POST /passkeys/auth` — le serveur vérifie : challenge ✓, origin ✓, type `webauthn.get` ✓
4. Le `credential_id` est recherché en BD, l'utilisateur associé est connecté

Les passkeys sont liés à un domaine précis (`rpId = getHost()`). Un passkey enregistré sur `laniproject.dev` ne fonctionnera pas sur `localhost` et vice-versa.

### 4. Double authentification (2FA — TOTP)

Utilise le standard **TOTP (Time-based One-Time Password)** via `pragmarx/google2fa-laravel`.

Flux d'activation :
1. `GET /settings/2fa/setup` — génère une clé secrète de 16 caractères, la stocke en BD (`two_factor_secret`)
2. Un QR code SVG est généré avec `bacon/bacon-qr-code` (encodé en base64 pour l'afficher inline)
3. L'utilisateur scanne le QR code dans Google Authenticator / Authy
4. `POST /settings/2fa/enable` — valide le premier code TOTP pour confirmer la configuration
5. `two_factor_enabled = true` est sauvegardé en BD

Le code TOTP change toutes les 30 secondes et est basé sur l'heure UTC + la clé secrète partagée.

---

## Chiffrement des mots de passe de services

Les mots de passe stockés dans le gestionnaire (YouTube, GitHub, etc.) utilisent le **chiffrement symétrique AES-256-CBC** via `Illuminate\Support\Facades\Crypt`.

```php
// Service.php — Accesseurs Eloquent automatiques

// Écriture → chiffré avant insertion en BD
public function setPasswordAttribute(string $value): void
{
    $this->attributes['password'] = Crypt::encryptString($value);
}

// Lecture → déchiffré à la volée lors de l'accès
public function getPasswordAttribute(string $value): string
{
    return Crypt::decryptString($value);
}
```

**Pourquoi symétrique et non un hash ?**
Un hash (bcrypt, SHA256) est irréversible — il est impossible de retrouver la valeur originale. Pour un gestionnaire de mots de passe, on doit pouvoir **afficher** le mot de passe à l'utilisateur, donc le chiffrement réversible est obligatoire.

**Clé de chiffrement :** `APP_KEY` dans le `.env` (générée par `php artisan key:generate`). Sans cette clé, toutes les données chiffrées en BD sont illisibles. Ne jamais régénérer cette clé en production.

**Affichage dans le dashboard :**
Le mot de passe est déchiffré côté serveur lors du rendu Blade, puis injecté dans un attribut `data-plain` du HTML. Il est masqué par défaut (`••••••••`) et révélé par JavaScript sans aucun appel réseau supplémentaire.

```html
<span data-plain="{{ $service->password }}" data-hidden="1">••••••••••••</span>
```

```javascript
function togglePassword(id) {
    const el = document.getElementById('pwd-' + id);
    el.textContent = el.dataset.hidden === '1' ? el.dataset.plain : '••••••••••••';
    el.dataset.hidden = el.dataset.hidden === '1' ? '0' : '1';
}
```

---

## Icônes des services

Système de fallback en cascade pour trouver le logo d'un service à partir de son URL.

```javascript
const sources = [
    `https://logo.clearbit.com/${domain}`,                        // API Clearbit Logo
    `https://${domain}/apple-touch-icon.png`,                     // Icône iOS native du site
    `https://${domain}/apple-touch-icon-precomposed.png`,
    `https://www.google.com/s2/favicons?domain=${domain}&sz=64`,  // Google Favicons API
];
```

**Ordre de priorité :**
1. **Clearbit Logo API** — retourne des logos haute résolution pour la majorité des services connus
2. **Apple Touch Icon** — fichier PNG présent sur la plupart des sites modernes (utilisé pour l'écran d'accueil iPhone)
3. **Apple Touch Icon Precomposed** — variante sans effets iOS appliqués
4. **Google Favicons API** — fallback universel, retourne le favicon du site en 64×64

Si aucune image ne se charge, une lettre de fallback (première lettre du nom du service) est affichée.

Les icônes sont chargées **côté client** (JavaScript) après le rendu de la page — elles ne transitent jamais par le serveur Laravel.

---

## Générateur de mots de passe

Le générateur utilise **`window.crypto.getRandomValues()`** — l'API cryptographique native du navigateur, bien plus sécurisée que `Math.random()`.

```javascript
function secureRandInt(max) {
    const arr = new Uint32Array(1);
    let val;
    do {
        window.crypto.getRandomValues(arr);
        val = arr[0];
    } while (val >= Math.floor(0xFFFFFFFF / max) * max); // Évite le biais modulo
    return val % max;
}
```

Le biais modulo est éliminé en rejetant les valeurs qui créeraient une distribution inégale.

**Caractéristiques :**
- Longueur configurable : 12 à 64 caractères
- 4 jeux de caractères activables : Majuscules, Minuscules, Chiffres, Symboles
- Garantit au moins 1 caractère de chaque catégorie activée
- Mélange final avec Fisher-Yates shuffle cryptographique

**Jauge de force (zxcvbn) :**
La bibliothèque **zxcvbn** (Dropbox) analyse la force du mot de passe en simulant des attaques réelles (dictionnaire, patterns, substitutions). Elle retourne un score de 0 à 4 et une estimation en bits d'entropie.

---

## Gestion des sessions

- Driver : `database` (table `sessions`)
- Durée : 120 minutes d'inactivité
- La page Paramètres liste toutes les sessions actives de l'utilisateur avec horodatage
- Possibilité de déconnecter une session spécifique ou toutes les autres sessions simultanément

---

## Sécurité générale

| Mécanisme | Implémentation |
|---|---|
| CSRF | Token Laravel sur chaque formulaire (`@csrf`) |
| Autorisation des ressources | `abort_unless($service->user_id === auth()->id(), 403)` |
| Headers de sécurité | `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff` |
| HTTPS forcé | Nginx redirige HTTP → HTTPS (301) |
| Sessions chiffrées | `SESSION_ENCRYPT=true` en production |
| `.env` ignoré | Jamais versionné dans Git |

---

## Structure des fichiers clés

```
app/
├── Http/Controllers/
│   ├── Auth/
│   │   ├── AuthenticatedSessionController.php  — Connexion classique
│   │   ├── OAuthController.php                 — OAuth Google/GitHub/Discord
│   │   └── RegisteredUserController.php        — Inscription
│   ├── PasskeyController.php                   — WebAuthn (enregistrement + login)
│   ├── ServiceController.php                   — CRUD des services (mots de passe)
│   └── SettingsController.php                  — Paramètres, 2FA, sessions
├── Models/
│   ├── User.php                                — Modèle utilisateur
│   ├── Service.php                             — Chiffrement AES-256-CBC
│   └── Passkey.php                             — Stockage des passkeys WebAuthn
routes/
├── web.php                                     — Routes principales + passkeys
└── auth.php                                    — Routes d'authentification
resources/views/
├── auth/login.blade.php                        — Page de connexion + passkey JS
├── dashboard.blade.php                         — Grille des services
├── services/create.blade.php                   — Formulaire d'ajout
├── services/edit.blade.php                     — Formulaire de modification
└── settings.blade.php                          — Paramètres, 2FA, passkeys, sessions
```

---

## Déploiement

- **Serveur :** AWS EC2 t2.micro, Ubuntu 24.04
- **IP fixe :** Elastic IP AWS (l'IP ne change pas au redémarrage)
- **DNS :** Porkbun → enregistrement A → Elastic IP
- **SSL :** Certbot (Let's Encrypt), renouvellement automatique via cron
- **Workflow de mise à jour :**

```bash
# Sur le Mac — après modifications
git add -A && git commit -m "description" && git push

# Sur EC2
cd /var/www/lockkeysecure
sudo git pull
sudo -u www-data php8.4 artisan migrate --force  # si nouvelles migrations
sudo -u www-data php8.4 artisan config:cache
sudo -u www-data php8.4 artisan route:cache
sudo -u www-data php8.4 artisan view:clear
sudo npm run build                               # si modifications CSS/JS
```