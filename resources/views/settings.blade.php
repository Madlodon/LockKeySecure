<!DOCTYPE html>
<html lang="fr" data-theme="green">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paramètres — LockKeySecure</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            const t = localStorage.getItem('lks_theme') || 'green';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --green:        #003200;
            --green-mid:    #004d00;
            --green-light:  #006600;
            --green-accent: #00aa00;
            --green-glow:   #009900;
            --radius:       10px;
        }
        body { font-family: Arial, sans-serif; background: #0d1f0d; color: #fff; min-height: 100vh; }

        /* ── TOPBAR ── */
        .topbar {
            height: 64px; background: var(--green);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,.4);
        }
        .brand { display: flex; align-items: center; gap: .6rem; text-decoration: none; color: #fff; }
        .brand span { font-size: 1.1rem; font-weight: 700; }
        .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .back-link {
            display: flex; align-items: center; gap: .4rem;
            color: rgba(255,255,255,.6); text-decoration: none; font-size: .875rem;
            transition: color .2s;
        }
        .back-link:hover { color: #fff; }

        /* ── LAYOUT ── */
        .page { max-width: 820px; margin: 0 auto; padding: 2.5rem 1.5rem; }
        .page-header { margin-bottom: 2rem; }
        .page-header h1 { font-size: 1.5rem; font-weight: 700; }
        .page-header p { color: rgba(255,255,255,.45); font-size: .875rem; margin-top: .25rem; }

        /* ── FLASH ── */
        .flash {
            background: rgba(0,170,0,.15); border: 1px solid rgba(0,170,0,.35);
            color: #a3ffb0; border-radius: var(--radius);
            padding: .75rem 1rem; font-size: .875rem; margin-bottom: 1.5rem;
        }
        .flash-error {
            background: rgba(200,0,0,.15); border-color: rgba(200,0,0,.4);
            color: #ffb0b0;
        }

        /* ── TABS ── */
        .tabs { display: flex; gap: .25rem; margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,.1); }
        .tab-btn {
            padding: .65rem 1.25rem; background: none; border: none;
            color: rgba(255,255,255,.45); font-size: .9rem; cursor: pointer;
            border-bottom: 2px solid transparent; margin-bottom: -1px;
            transition: color .2s, border-color .2s; font-family: Arial, sans-serif;
            text-decoration: none; display: flex; align-items: center; gap: .5rem;
        }
        .tab-btn:hover { color: rgba(255,255,255,.75); }
        .tab-btn.active { color: var(--green-accent); border-bottom-color: var(--green-accent); }

        /* ── TAB PANELS ── */
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* ── SECTION CARD ── */
        .section {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: var(--radius);
            padding: 1.75rem;
            margin-bottom: 1.25rem;
        }
        .section h2 { font-size: 1rem; font-weight: 700; margin-bottom: .35rem; }
        .section-desc { font-size: .85rem; color: rgba(255,255,255,.45); margin-bottom: 1.25rem; line-height: 1.5; }
        .section-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }

        /* ── FIELDS ── */
        .field { margin-bottom: 1rem; }
        .field label { display: block; color: rgba(255,255,255,.7); font-size: .875rem; margin-bottom: .4rem; }
        .field input {
            width: 100%; padding: .6rem .9rem;
            border-radius: var(--radius); border: 1px solid rgba(255,255,255,.18);
            background: rgba(255,255,255,.07); color: #fff;
            font-size: .9rem; outline: none; transition: border-color .2s;
            font-family: Arial, sans-serif;
        }
        .field input::placeholder { color: rgba(255,255,255,.3); }
        .field input:focus { border-color: var(--green-accent); }
        .field-error { color: #ffb0b0; font-size: .8rem; margin-top: .3rem; }

        /* ── BUTTONS ── */
        .btn {
            padding: .55rem 1.2rem; border-radius: var(--radius);
            font-size: .875rem; font-weight: 600; cursor: pointer;
            font-family: Arial, sans-serif; transition: all .2s;
            border: none; text-decoration: none; display: inline-flex; align-items: center; gap: .4rem;
        }
        .btn-primary { background: var(--green-accent); color: #fff; }
        .btn-primary:hover { background: var(--green-glow); }
        .btn-ghost { background: transparent; border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.75); }
        .btn-ghost:hover { background: rgba(255,255,255,.08); color: #fff; }
        .btn-danger { background: transparent; border: 1px solid rgba(200,0,0,.4); color: rgba(255,100,100,.8); }
        .btn-danger:hover { background: rgba(200,0,0,.15); color: #ff8080; }
        .btn-sm { padding: .3rem .75rem; font-size: .78rem; }

        /* ── 2FA ── */
        .badge {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: .25rem .75rem; border-radius: 99px; font-size: .78rem; font-weight: 700;
        }
        .badge-on  { background: rgba(0,170,0,.2); color: #5dff7a; border: 1px solid rgba(0,170,0,.4); }
        .badge-off { background: rgba(200,0,0,.15); color: #ff8080; border: 1px solid rgba(200,0,0,.3); }

        .qr-wrap {
            background: #fff; border-radius: 8px; padding: 12px;
            display: inline-block; margin-bottom: 1rem;
        }
        .qr-wrap img { display: block; }
        .secret-code {
            font-family: monospace; font-size: 1.1rem; letter-spacing: .15em;
            color: var(--green-accent); background: rgba(0,0,0,.3);
            border: 1px solid rgba(255,255,255,.1); border-radius: 6px;
            padding: .5rem 1rem; margin-bottom: 1rem; word-break: break-all;
        }
        .otp-input {
            font-size: 1.5rem !important; letter-spacing: .4em !important;
            text-align: center; max-width: 220px;
            font-family: monospace !important;
        }

        /* ── GENERATOR (password tab) ── */
        .pwd-wrap { position: relative; }
        .pwd-wrap input { padding-right: 2.8rem; }
        .pwd-toggle {
            position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: rgba(255,255,255,.4);
            cursor: pointer; display: flex; align-items: center; transition: color .2s;
        }
        .pwd-toggle:hover { color: var(--green-accent); }
        .entropy-wrap { margin-top: .5rem; margin-bottom: .5rem; }
        .entropy-bar-bg { height: 5px; background: rgba(255,255,255,.12); border-radius: 99px; overflow: hidden; }
        .entropy-bar { height: 100%; width: 0%; border-radius: 99px; transition: width .3s, background .3s; }
        .entropy-label { font-size: .75rem; margin-top: .25rem; }
        .gen-toggle-btn {
            background: none; border: 1px solid rgba(255,255,255,.2);
            color: rgba(255,255,255,.6); border-radius: var(--radius);
            padding: .3rem .8rem; font-size: .78rem; cursor: pointer;
            margin-top: .4rem; transition: all .2s; font-family: Arial, sans-serif;
        }
        .gen-toggle-btn:hover { background: rgba(255,255,255,.08); color: #fff; }
        .generator {
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.1);
            border-radius: var(--radius); padding: 1rem; margin-top: .6rem;
        }
        .gen-row { display: flex; align-items: center; gap: .75rem; margin-bottom: .75rem; flex-wrap: wrap; }
        .gen-row label { font-size: .8rem; color: rgba(255,255,255,.6); white-space: nowrap; }
        .gen-row input[type="range"] { flex: 1; min-width: 120px; accent-color: var(--green-accent); cursor: pointer; }
        .gen-length-val { font-size: .85rem; font-weight: 700; color: var(--green-accent); min-width: 28px; text-align: right; }
        .gen-pills { display: flex; gap: .45rem; flex-wrap: wrap; margin-bottom: .75rem; }
        .gen-pill {
            padding: .25rem .8rem; border-radius: 99px;
            border: 1px solid rgba(255,255,255,.2);
            background: transparent; color: rgba(255,255,255,.4);
            font-size: .78rem; cursor: pointer; transition: all .2s;
            font-family: Arial, sans-serif; user-select: none;
        }
        .gen-pill.on { background: rgba(0,170,0,.2); border-color: var(--green-accent); color: #fff; font-weight: 600; }
        .gen-pill:hover:not(.on) { border-color: rgba(255,255,255,.4); color: rgba(255,255,255,.7); }
        .btn-generate {
            background: var(--green-accent); color: #fff; border: none;
            border-radius: var(--radius); padding: .4rem .9rem;
            font-size: .8rem; font-weight: 700; cursor: pointer;
            transition: background .2s; font-family: Arial, sans-serif;
        }
        .btn-generate:hover { background: var(--green-glow); }

        /* ── PASSKEY ── */
        .passkey-list { display: flex; flex-direction: column; gap: .6rem; margin-top: 1rem; }
        .passkey-item {
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px; padding: .75rem 1rem; gap: 1rem;
        }
        .passkey-info { font-size: .875rem; }
        .passkey-name { font-weight: 600; margin-bottom: .15rem; }
        .passkey-meta { color: rgba(255,255,255,.4); font-size: .78rem; }

        /* ── THÈMES ── */
        .theme-card {
            background: rgba(255,255,255,.05);
            border: 2px solid rgba(255,255,255,.12);
            border-radius: var(--radius);
            padding: .85rem;
            width: 140px;
            cursor: pointer;
            font-size: .85rem;
            color: rgba(255,255,255,.7);
            text-align: center;
            transition: border-color .2s, transform .15s;
            font-family: Arial, sans-serif;
        }
        .theme-card:hover { border-color: rgba(255,255,255,.3); transform: translateY(-2px); }
        .theme-card.active-theme { border-color: var(--green-accent); color: #fff; }

        /* ── SESSIONS ── */
        .session-list { display: flex; flex-direction: column; gap: .75rem; }
        .session-item {
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px; padding: .85rem 1rem; gap: 1rem; flex-wrap: wrap;
        }
        .session-item.current { border-color: rgba(0,170,0,.35); background: rgba(0,170,0,.07); }
        .session-info { flex: 1; }
        .session-agent { font-size: .875rem; font-weight: 600; margin-bottom: .25rem; word-break: break-all; }
        .session-meta { display: flex; gap: 1rem; flex-wrap: wrap; }
        .session-meta span { font-size: .78rem; color: rgba(255,255,255,.4); }
        .session-meta .current-tag { color: var(--green-accent); font-weight: 600; }
        .sessions-footer { margin-top: 1rem; }
    </style>
</head>
<body>

<nav class="topbar">
    <a href="{{ route('dashboard') }}" class="brand">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#00aa00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
        <span>LockKeySecure</span>
    </a>
    <a href="{{ route('dashboard') }}" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
        Retour au dashboard
    </a>
</nav>

<div class="page">

    <div class="page-header">
        <h1>Paramètres</h1>
        <p>Sécurité et gestion de ton compte</p>
    </div>

    @if (session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    @if ($errors->any() && ! $errors->has('code') && ! $errors->has('current_password') && ! $errors->has('new_password'))
        <div class="flash flash-error">
            @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <!-- ── TABS ── -->
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('2fa', this)">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
            Double authentification
        </button>
        <button class="tab-btn" onclick="switchTab('passkey', this)">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
            Passkey
        </button>
        <button class="tab-btn" onclick="switchTab('sessions', this)">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            Sessions actives
        </button>
        <button class="tab-btn" onclick="switchTab('password', this)">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Mot de passe
        </button>
        <button class="tab-btn" onclick="switchTab('apparence', this)">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20"/><path d="M12 2v20"/></svg>
            Apparence
        </button>
    </div>

    <!-- ══════════════════════════════════════════
         TAB : 2FA
    ══════════════════════════════════════════ -->
    <div class="tab-panel active" id="tab-2fa">
        <div class="section">
            <div class="section-row" style="margin-bottom:1.25rem;">
                <div>
                    <h2>Google Authenticator</h2>
                    <p class="section-desc" style="margin:0;">Ajoute une deuxième couche de sécurité avec une application d'authentification (Google Authenticator, Authy, etc.).</p>
                </div>
                @if (auth()->user()->two_factor_enabled)
                    <span class="badge badge-on">● Activé</span>
                @else
                    <span class="badge badge-off">○ Désactivé</span>
                @endif
            </div>

            @if (auth()->user()->two_factor_enabled)
                {{-- 2FA déjà activé --}}
                <p style="font-size:.875rem;color:rgba(255,255,255,.6);margin-bottom:1.25rem;">
                    La double authentification est active. Chaque connexion demande un code de ton application.
                </p>
                <form method="POST" action="{{ route('settings.2fa.disable') }}" onsubmit="return confirm('Désactiver la double authentification ?')">
                    @csrf
                    <button type="submit" class="btn btn-danger">Désactiver le 2FA</button>
                </form>

            @elseif (isset($qrSvg))
                {{-- Étape d'activation : scanner le QR --}}
                <p class="section-desc">
                    1. Scanne ce QR code dans Google Authenticator (ou Authy).<br>
                    2. Entre le code à 6 chiffres pour confirmer.
                </p>
                <div class="qr-wrap">
                    <img src="data:image/svg+xml;base64,{{ $qrSvg }}" alt="QR Code 2FA" width="200" height="200">
                </div>
                <p style="font-size:.8rem;color:rgba(255,255,255,.45);margin-bottom:.5rem;">Ou entre la clé manuellement :</p>
                <div class="secret-code">{{ $secret }}</div>

                <form method="POST" action="{{ route('settings.2fa.enable') }}" style="max-width:300px;">
                    @csrf
                    <div class="field">
                        <label for="code">Code de vérification</label>
                        <input id="code" type="text" name="code" maxlength="6" inputmode="numeric" class="otp-input" placeholder="000000" autofocus>
                        @error('code')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Confirmer et activer</button>
                </form>

            @else
                {{-- Pas encore configuré --}}
                <p class="section-desc">
                    Protège ton coffre-fort avec un code temporaire généré par ton téléphone.
                </p>
                <a href="{{ route('settings.2fa.setup') }}" class="btn btn-primary">Configurer le 2FA</a>
            @endif
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         TAB : PASSKEY
    ══════════════════════════════════════════ -->
    <div class="tab-panel" id="tab-passkey">
        <div class="section">
            <h2>Passkeys (clés d'accès)</h2>
            <p class="section-desc">
                Connecte-toi sans mot de passe grâce à la biométrie de ton appareil (Face ID, Touch ID, Windows Hello…). Plus rapide et plus sécurisé qu'un mot de passe.
            </p>

            <div id="passkey-support-msg" style="display:none;margin-bottom:1.25rem;">
                <div class="flash flash-error" id="passkey-no-support" style="display:none;">
                    Ton navigateur ne supporte pas les Passkeys. Utilise Chrome, Edge ou Safari récent.
                </div>
            </div>

            <button type="button" class="btn btn-primary" id="btn-register-passkey" onclick="registerPasskey()">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Ajouter un passkey
            </button>

            <div id="passkey-status" style="margin-top:.75rem;font-size:.875rem;color:rgba(255,255,255,.5);"></div>
        </div>

        <div class="section">
            <h2 style="margin-bottom:.75rem;">Passkeys enregistrés</h2>
            <div class="passkey-list" id="passkey-list">
                @forelse ($passkeys as $pk)
                    <div class="passkey-item" id="passkey-item-{{ $pk->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;color:var(--green-accent)"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <div class="passkey-info">
                            <div class="passkey-name">{{ $pk->name }}</div>
                            <div class="passkey-meta">Ajouté le {{ $pk->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deletePasskey({{ $pk->id }}, this)">Supprimer</button>
                    </div>
                @empty
                    <p id="passkey-empty" style="font-size:.85rem;color:rgba(255,255,255,.4);">Aucun passkey enregistré pour l'instant.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         TAB : SESSIONS
    ══════════════════════════════════════════ -->
    <div class="tab-panel" id="tab-sessions">
        <div class="section">
            <div class="section-row" style="margin-bottom:1.25rem;">
                <div>
                    <h2>Sessions actives</h2>
                    <p class="section-desc" style="margin:0;">Tous les appareils actuellement connectés à ton compte.</p>
                </div>
                @if ($sessions->count() > 1)
                    <form method="POST" action="{{ route('settings.sessions.destroy-all') }}" onsubmit="return confirm('Déconnecter toutes les autres sessions ?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">Tout déconnecter</button>
                    </form>
                @endif
            </div>

            <div class="session-list">
                @forelse ($sessions as $session)
                    <div class="session-item {{ $session->is_current ? 'current' : '' }}">
                        <div class="session-info">
                            <div class="session-agent">
                                {{ Str::limit($session->user_agent ?? 'Navigateur inconnu', 80) }}
                            </div>
                            <div class="session-meta">
                                @if ($session->ip_address)
                                    <span>📍 {{ $session->ip_address }}</span>
                                @endif
                                <span>⏱ {{ $session->last_activity_human }}</span>
                                @if ($session->is_current)
                                    <span class="current-tag">✓ Session actuelle</span>
                                @endif
                            </div>
                        </div>
                        @if (! $session->is_current)
                            <form method="POST" action="{{ route('settings.sessions.destroy', $session->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="white-space:nowrap;">Déconnecter</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p style="color:rgba(255,255,255,.4);font-size:.875rem;">Aucune session trouvée.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         TAB : APPARENCE
    ══════════════════════════════════════════ -->
    <div class="tab-panel" id="tab-apparence">
        <div class="section">
            <h2>Thème de l'interface</h2>
            <p class="section-desc">Choisis l'apparence du site. Le thème est sauvegardé dans ton navigateur.</p>

            <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-top:1rem;">

                <button onclick="setTheme('green')" id="theme-btn-green" class="theme-card active-theme">
                    <div style="width:100%;height:60px;border-radius:6px;background:linear-gradient(135deg,#003200,#006600);margin-bottom:.6rem;"></div>
                    <span>Vert (défaut)</span>
                </button>

                <button onclick="setTheme('dark')" id="theme-btn-dark" class="theme-card">
                    <div style="width:100%;height:60px;border-radius:6px;background:linear-gradient(135deg,#050508,#111118);margin-bottom:.6rem;"></div>
                    <span>Sombre</span>
                </button>

                <button onclick="setTheme('light')" id="theme-btn-light" class="theme-card">
                    <div style="width:100%;height:60px;border-radius:6px;background:linear-gradient(135deg,#1e6b1e,#2d8f2d);margin-bottom:.6rem;"></div>
                    <span>Vert clair</span>
                </button>

                <button onclick="setTheme('white')" id="theme-btn-white" class="theme-card">
                    <div style="width:100%;height:60px;border-radius:6px;background:linear-gradient(135deg,#f0f2f0,#ffffff);border:1px solid rgba(0,0,0,.1);margin-bottom:.6rem;"></div>
                    <span>Blanc</span>
                </button>

            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         TAB : MOT DE PASSE
    ══════════════════════════════════════════ -->
    <div class="tab-panel" id="tab-password">
        <div class="section">
            <h2>Changer le mot de passe</h2>
            <p class="section-desc">
                @if (auth()->user()->password)
                    Utilise un mot de passe fort et unique.
                @else
                    Ton compte a été créé via OAuth. Tu peux définir un mot de passe pour pouvoir aussi te connecter avec ton courriel.
                @endif
            </p>

            <form method="POST" action="{{ route('settings.password') }}" style="max-width:420px;">
                @csrf

                @if (auth()->user()->password)
                    <div class="field">
                        <label for="current_password">Mot de passe actuel</label>
                        <input id="current_password" type="password" name="current_password" placeholder="••••••••">
                        @error('current_password')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                @endif

                <div class="field">
                    <label for="new_password">Nouveau mot de passe</label>
                    <div class="pwd-wrap">
                        <input id="new_password" type="password" name="new_password" placeholder="••••••••" minlength="8" oninput="updateSettingsEntropy(this.value)">
                        <button type="button" class="pwd-toggle" onclick="toggleNewPwd()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <div class="entropy-wrap" id="s-entropy-wrap" style="display:none;">
                        <div class="entropy-bar-bg"><div class="entropy-bar" id="s-entropy-bar"></div></div>
                        <div class="entropy-label" id="s-entropy-label"></div>
                    </div>
                    <button type="button" class="gen-toggle-btn" onclick="toggleSettingsGen()">⚙ Générer un mot de passe</button>
                    <div class="generator" id="s-generator" style="display:none;">
                        <div class="gen-row">
                            <label>Longueur :</label>
                            <input type="range" id="s-gen-length" min="12" max="64" value="20" oninput="document.getElementById('s-gen-length-val').textContent = this.value">
                            <span class="gen-length-val" id="s-gen-length-val">20</span>
                        </div>
                        <div class="gen-pills">
                            <button type="button" class="gen-pill on" id="s-gen-upper" onclick="togglePill(this)">A–Z Maj</button>
                            <button type="button" class="gen-pill on" id="s-gen-lower" onclick="togglePill(this)">a–z Min</button>
                            <button type="button" class="gen-pill on" id="s-gen-digits" onclick="togglePill(this)">0–9 Chiffres</button>
                            <button type="button" class="gen-pill" id="s-gen-symbols" onclick="togglePill(this)">!@# Symboles</button>
                        </div>
                        <button type="button" class="btn-generate" onclick="generateSettingsPassword()">↻ Générer</button>
                    </div>
                    @error('new_password')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="new_password_confirmation">Confirmer le mot de passe</label>
                    <input id="new_password_confirmation" type="password" name="new_password_confirmation" placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>

</div>

<script>
    /* ── Thèmes ── */
    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('lks_theme', theme);
        document.querySelectorAll('.theme-card').forEach(b => b.classList.remove('active-theme'));
        document.getElementById('theme-btn-' + theme).classList.add('active-theme');
    }

    // Marquer le bouton du thème actif au chargement
    (function() {
        const t = localStorage.getItem('lks_theme') || 'green';
        const btn = document.getElementById('theme-btn-' + t);
        if (btn) {
            document.querySelectorAll('.theme-card').forEach(b => b.classList.remove('active-theme'));
            btn.classList.add('active-theme');
        }
    })();

    /* ── Onglets ── */
    function switchTab(name, btn) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + name).classList.add('active');
        btn.classList.add('active');
    }

    /* ── Restaurer l'onglet actif après erreur de formulaire ── */
    const activeTab = '{{ session("tab", "2fa") }}';
    @if ($errors->has('current_password') || $errors->has('new_password'))
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('tab-password').classList.add('active');
        document.querySelector('[onclick="switchTab(\'password\', this)"]').classList.add('active');
    @elseif ($errors->has('code'))
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('tab-2fa').classList.add('active');
        document.querySelector('[onclick="switchTab(\'2fa\', this)"]').classList.add('active');
    @endif

    /* ── Générateur (onglet mot de passe) ── */
    function toggleNewPwd() {
        const i = document.getElementById('new_password');
        i.type = i.type === 'password' ? 'text' : 'password';
    }
    function toggleSettingsGen() {
        const g = document.getElementById('s-generator');
        g.style.display = g.style.display === 'none' ? 'block' : 'none';
    }
    function togglePill(btn) { btn.classList.toggle('on'); }

    const sColors = ['#cc2200','#e06000','#e0a000','#3a9a00','#00aa00'];
    const sLabels = ['Très faible','Faible','Moyen','Fort','Très fort'];
    function updateSettingsEntropy(value) {
        const wrap = document.getElementById('s-entropy-wrap');
        const bar  = document.getElementById('s-entropy-bar');
        const lbl  = document.getElementById('s-entropy-label');
        if (!value) { wrap.style.display = 'none'; return; }
        wrap.style.display = 'block';
        const r = window.zxcvbn(value);
        bar.style.width = ((r.score + 1) * 20) + '%';
        bar.style.background = sColors[r.score];
        lbl.textContent = sLabels[r.score] + ' — ~' + Math.round(r.guesses_log10 * 3.32) + ' bits';
        lbl.style.color = sColors[r.score];
    }
    function secureRandInt(max) {
        const arr = new Uint32Array(1); let v;
        do { window.crypto.getRandomValues(arr); v = arr[0]; }
        while (v >= Math.floor(0xFFFFFFFF / max) * max);
        return v % max;
    }
    function shuffle(a) {
        for (let i = a.length - 1; i > 0; i--) { const j = secureRandInt(i+1); [a[i],a[j]] = [a[j],a[i]]; }
        return a;
    }
    function generateSettingsPassword() {
        const length = parseInt(document.getElementById('s-gen-length').value);
        const sets = {
            upper:   { active: document.getElementById('s-gen-upper').classList.contains('on'),   chars: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' },
            lower:   { active: document.getElementById('s-gen-lower').classList.contains('on'),   chars: 'abcdefghijklmnopqrstuvwxyz' },
            digits:  { active: document.getElementById('s-gen-digits').classList.contains('on'),  chars: '0123456789' },
            symbols: { active: document.getElementById('s-gen-symbols').classList.contains('on'), chars: '!@#$%^&*()-_=+[]{}|;:,.<>?' },
        };
        const active = Object.values(sets).filter(s => s.active);
        if (!active.length) {
            ['s-gen-upper','s-gen-lower','s-gen-digits'].forEach(id => document.getElementById(id).classList.add('on'));
            return generateSettingsPassword();
        }
        const full = active.map(s => s.chars).join('');
        const result = active.map(s => s.chars[secureRandInt(s.chars.length)]);
        while (result.length < length) result.push(full[secureRandInt(full.length)]);
        const pwd = shuffle(result).join('');
        const input = document.getElementById('new_password');
        input.type = 'text';
        input.value = pwd;
        updateSettingsEntropy(pwd);
    }

    /* ── Passkey (WebAuthn) ── */
    window.addEventListener('DOMContentLoaded', () => {
        if (! window.PublicKeyCredential) {
            document.getElementById('passkey-support-msg').style.display = 'block';
            document.getElementById('passkey-no-support').style.display = 'block';
            document.getElementById('btn-register-passkey').disabled = true;
            document.getElementById('btn-register-passkey').style.opacity = '.4';
        }
    });

    function base64urlToUint8Array(base64url) {
        const base64 = base64url.replace(/-/g, '+').replace(/_/g, '/');
        const bin = atob(base64);
        return Uint8Array.from(bin, c => c.charCodeAt(0));
    }

    function uint8ArrayToBase64url(arr) {
        let bin = '';
        arr.forEach(b => bin += String.fromCharCode(b));
        return btoa(bin).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
    }

    async function registerPasskey() {
        const status = document.getElementById('passkey-status');
        status.style.color = 'rgba(255,255,255,.5)';
        status.textContent = 'Récupération du challenge…';

        try {
            // 1. Obtenir les options et le challenge depuis le serveur
            const optRes = await fetch('{{ route('passkeys.options') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            });
            const options = await optRes.json();

            // 2. Convertir le challenge base64url → Uint8Array pour l'API WebAuthn
            options.challenge = base64urlToUint8Array(options.challenge);
            options.user.id   = base64urlToUint8Array(options.user.id);

            status.textContent = 'En attente de ton authentificateur…';

            // 3. Créer le passkey sur l'appareil
            const credential = await navigator.credentials.create({ publicKey: options });

            // 4. Encoder la réponse en base64url pour l'envoyer au serveur
            const payload = {
                id:                credential.id,
                rawId:             uint8ArrayToBase64url(new Uint8Array(credential.rawId)),
                clientDataJSON:    uint8ArrayToBase64url(new Uint8Array(credential.response.clientDataJSON)),
                attestationObject: uint8ArrayToBase64url(new Uint8Array(credential.response.attestationObject)),
                name:              null,
            };

            // 5. Enregistrer sur le serveur
            const storeRes = await fetch('{{ route('passkeys.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(payload),
            });

            const result = await storeRes.json();

            if (! storeRes.ok) {
                throw new Error(result.error || 'Erreur serveur.');
            }

            status.style.color = '#5dff7a';
            status.textContent = '✓ Passkey enregistré avec succès !';

            // Ajouter le passkey dans la liste sans recharger la page
            const list  = document.getElementById('passkey-list');
            const empty = document.getElementById('passkey-empty');
            if (empty) empty.remove();

            list.insertAdjacentHTML('beforeend', `
                <div class="passkey-item" id="passkey-item-${result.passkey.id}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;color:var(--green-accent)"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <div class="passkey-info">
                        <div class="passkey-name">${result.passkey.name}</div>
                        <div class="passkey-meta">Ajouté le ${result.passkey.created_at}</div>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deletePasskey(${result.passkey.id}, this)">Supprimer</button>
                </div>
            `);

        } catch (err) {
            status.style.color = '#ff8080';
            status.textContent = 'Erreur : ' + err.message;
        }
    }

    async function deletePasskey(id, btn) {
        if (! confirm('Supprimer ce passkey ?')) return;
        btn.disabled = true;
        try {
            const res = await fetch(`/passkeys/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            });
            if (res.ok) {
                const item = document.getElementById('passkey-item-' + id);
                item.remove();
                const list = document.getElementById('passkey-list');
                if (list.children.length === 0) {
                    list.innerHTML = '<p id="passkey-empty" style="font-size:.85rem;color:rgba(255,255,255,.4);">Aucun passkey enregistré pour l\'instant.</p>';
                }
            }
        } catch {
            btn.disabled = false;
        }
    }
</script>

</body>
</html>
