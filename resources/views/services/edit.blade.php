<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier — {{ $service->name }} — LockKeySecure</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

        .topbar {
            height: 64px; background: var(--green);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,.4);
        }
        .brand { display: flex; align-items: center; gap: .6rem; text-decoration: none; color: #fff; }
        .brand span { font-size: 1.1rem; font-weight: 700; }
        .back-link {
            display: flex; align-items: center; gap: .4rem;
            color: rgba(255,255,255,.6); text-decoration: none; font-size: .875rem;
            transition: color .2s;
        }
        .back-link:hover { color: #fff; }

        .page { max-width: 680px; margin: 0 auto; padding: 2.5rem 1.5rem; }
        .page-title { display: flex; align-items: center; gap: .85rem; margin-bottom: 2rem; }
        .page-title-icon {
            width: 42px; height: 42px; border-radius: 8px;
            background: rgba(255,255,255,.1); overflow: hidden;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            font-weight: 700; color: var(--green-accent); font-size: 1.2rem;
        }
        .page-title-icon img { width: 100%; height: 100%; object-fit: contain; }
        .page-title h1 { font-size: 1.5rem; font-weight: 700; }

        .field { margin-bottom: 1.2rem; }
        .field label { display: block; color: rgba(255,255,255,.7); font-size: .875rem; margin-bottom: .4rem; }
        .field input, .field textarea {
            width: 100%; padding: .65rem .9rem;
            border-radius: var(--radius);
            border: 1px solid rgba(255,255,255,.18);
            background: rgba(255,255,255,.07); color: #fff;
            font-size: .95rem; font-family: Arial, sans-serif;
            outline: none; transition: border-color .2s;
        }
        .field input::placeholder, .field textarea::placeholder { color: rgba(255,255,255,.3); }
        .field input:focus, .field textarea:focus { border-color: var(--green-accent); }
        .field textarea { resize: vertical; min-height: 90px; }
        .field-error { color: #ffb0b0; font-size: .8rem; margin-top: .3rem; }

        .pwd-wrap { position: relative; }
        .pwd-wrap input { padding-right: 2.8rem; }
        .pwd-toggle {
            position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: rgba(255,255,255,.4);
            cursor: pointer; display: flex; align-items: center; transition: color .2s;
        }
        .pwd-toggle:hover { color: var(--green-accent); }

        .entropy-wrap { margin-top: .5rem; }
        .entropy-bar-bg { height: 6px; background: rgba(255,255,255,.12); border-radius: 99px; overflow: hidden; }
        .entropy-bar { height: 100%; width: 0%; border-radius: 99px; transition: width .3s, background .3s; }
        .entropy-label { font-size: .78rem; color: rgba(255,255,255,.45); margin-top: .3rem; }

        .gen-toggle-btn {
            background: none; border: 1px solid rgba(255,255,255,.2);
            color: rgba(255,255,255,.6); border-radius: var(--radius);
            padding: .35rem .85rem; font-size: .8rem; cursor: pointer;
            margin-top: .5rem; transition: all .2s; font-family: Arial, sans-serif;
        }
        .gen-toggle-btn:hover { background: rgba(255,255,255,.08); color: #fff; }

        .generator {
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.12);
            border-radius: var(--radius); padding: 1.1rem 1.1rem .9rem; margin-top: .75rem;
        }
        .gen-row { display: flex; align-items: center; gap: .75rem; margin-bottom: .85rem; flex-wrap: wrap; }
        .gen-row label { font-size: .8rem; color: rgba(255,255,255,.6); white-space: nowrap; }
        .gen-row input[type="range"] { flex: 1; min-width: 120px; accent-color: var(--green-accent); cursor: pointer; }
        .gen-length-val { font-size: .85rem; font-weight: 700; color: var(--green-accent); min-width: 28px; text-align: right; }

        .gen-pills { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: .9rem; }
        .gen-pill {
            padding: .3rem .9rem; border-radius: 99px;
            border: 1px solid rgba(255,255,255,.2);
            background: transparent; color: rgba(255,255,255,.4);
            font-size: .8rem; cursor: pointer; transition: all .2s;
            font-family: Arial, sans-serif; user-select: none;
        }
        .gen-pill.on {
            background: rgba(0,170,0,.2); border-color: var(--green-accent);
            color: #fff; font-weight: 600;
        }
        .gen-pill:hover:not(.on) { border-color: rgba(255,255,255,.4); color: rgba(255,255,255,.7); }

        .btn-generate {
            background: var(--green-accent); color: #fff; border: none;
            border-radius: var(--radius); padding: .5rem 1.1rem;
            font-size: .875rem; font-weight: 700; cursor: pointer;
            transition: background .2s; font-family: Arial, sans-serif;
        }
        .btn-generate:hover { background: var(--green-glow); }

        .form-actions { display: flex; gap: .75rem; margin-top: 2rem; flex-wrap: wrap; }
        .btn-submit {
            flex: 1; padding: .7rem; border-radius: var(--radius);
            border: none; background: var(--green-accent); color: #fff;
            font-size: 1rem; font-weight: 700; cursor: pointer;
            transition: background .2s; font-family: Arial, sans-serif; min-width: 150px;
        }
        .btn-submit:hover { background: var(--green-glow); }
        .btn-cancel {
            padding: .7rem 1.5rem; border-radius: var(--radius);
            border: 1px solid rgba(255,255,255,.2); background: transparent;
            color: rgba(255,255,255,.6); font-size: 1rem; cursor: pointer;
            text-decoration: none; display: flex; align-items: center;
            transition: all .2s; font-family: Arial, sans-serif;
        }
        .btn-cancel:hover { background: rgba(255,255,255,.08); color: #fff; }
        .btn-delete-service {
            padding: .7rem 1.2rem; border-radius: var(--radius);
            border: 1px solid rgba(200,0,0,.4); background: transparent;
            color: rgba(255,100,100,.7); font-size: 1rem; cursor: pointer;
            transition: all .2s; font-family: Arial, sans-serif;
        }
        .btn-delete-service:hover { background: rgba(200,0,0,.2); color: #ff8080; }

        .url-row { display: flex; align-items: center; gap: .75rem; }
        .icon-preview {
            width: 36px; height: 36px; border-radius: 8px;
            background: rgba(255,255,255,.1); overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-weight: 700; color: var(--green-accent); font-size: 1rem;
        }
        .icon-preview img { width: 100%; height: 100%; object-fit: contain; }
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
        Retour
    </a>
</nav>

<div class="page">

    <div class="page-title">
        <div class="page-title-icon" id="page-icon"
             @if($service->url) data-icon-domain="{{ parse_url($service->url, PHP_URL_HOST) }}" @endif>
            <img class="svc-icon-img" src="" alt="{{ $service->name }}" style="display:none;width:100%;height:100%;object-fit:contain;">
            <span class="svc-icon-fallback" @if($service->url) style="display:none;" @endif>
                {{ strtoupper(substr($service->name, 0, 1)) }}
            </span>
        </div>
        <h1>Modifier {{ $service->name }}</h1>
    </div>

    @if ($errors->any())
        <div style="background:rgba(200,0,0,.15);border:1px solid rgba(200,0,0,.4);color:#ffb0b0;border-radius:10px;padding:.75rem 1rem;font-size:.875rem;margin-bottom:1.25rem;">
            @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('services.update', $service) }}">
        @csrf
        @method('PUT')

        <div class="field">
            <label for="name">Nom du service *</label>
            <input id="name" type="text" name="name" value="{{ old('name', $service->name) }}" required placeholder="YouTube, Reddit, GitHub…">
            @error('name')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="url">URL du site</label>
            <div class="url-row">
                <div class="icon-preview" id="icon-preview">
                    @if ($service->url)
                        <img id="icon-img"
                             src="https://www.google.com/s2/favicons?domain={{ parse_url($service->url, PHP_URL_HOST) }}&sz=64"
                             alt="{{ $service->name }}"
                             onerror="this.style.display='none'; document.getElementById('icon-letter').style.display='block';">
                        <span id="icon-letter" style="display:none;">{{ strtoupper(substr($service->name, 0, 1)) }}</span>
                    @else
                        <img id="icon-img" src="" alt="" style="display:none;" onerror="this.style.display='none'; document.getElementById('icon-letter').style.display='block';">
                        <span id="icon-letter">{{ strtoupper(substr($service->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div style="flex:1;">
                    <input id="url" type="text" name="url" value="{{ old('url', $service->url) }}" placeholder="youtube.com" oninput="updateIconFromUrl(this.value)" onblur="normalizeUrl(this)">
                </div>
            </div>
            @error('url')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="account_id">Identifiant / Courriel du compte</label>
            <input id="account_id" type="text" name="account_id" value="{{ old('account_id', $service->account_id) }}" placeholder="mon@courriel.com">
            @error('account_id')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="password">Mot de passe *</label>
            <div class="pwd-wrap">
                <input id="password" type="password" name="password" value="{{ old('password', $service->password) }}" required placeholder="••••••••" oninput="updateEntropy(this.value)" autocomplete="new-password">
                <button type="button" class="pwd-toggle" onclick="togglePwd()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>

            <div class="entropy-wrap" id="entropy-wrap">
                <div class="entropy-bar-bg"><div class="entropy-bar" id="entropy-bar"></div></div>
                <div class="entropy-label" id="entropy-label"></div>
            </div>

            <button type="button" class="gen-toggle-btn" onclick="toggleGenerator()">⚙ Générer un mot de passe</button>

            <div class="generator" id="generator" style="display:none;">
                <div class="gen-row">
                    <label for="gen-length">Longueur :</label>
                    <input type="range" id="gen-length" min="12" max="64" value="20" oninput="document.getElementById('gen-length-val').textContent = this.value">
                    <span class="gen-length-val" id="gen-length-val">20</span>
                </div>
                <div class="gen-pills">
                    <button type="button" class="gen-pill on" id="gen-upper" onclick="togglePill(this)">A–Z Maj</button>
                    <button type="button" class="gen-pill on" id="gen-lower" onclick="togglePill(this)">a–z Min</button>
                    <button type="button" class="gen-pill on" id="gen-digits" onclick="togglePill(this)">0–9 Chiffres</button>
                    <button type="button" class="gen-pill" id="gen-symbols" onclick="togglePill(this)">!@# Symboles</button>
                </div>
                <button type="button" class="btn-generate" onclick="generatePassword()">↻ Générer</button>
            </div>

            @error('password')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" placeholder="Questions de sécurité, remarques…">{{ old('notes', $service->notes) }}</textarea>
            @error('notes')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('dashboard') }}" class="btn-cancel">Annuler</a>
            <button type="submit" class="btn-submit">Enregistrer les modifications</button>
        </div>
    </form>

    {{-- Suppression ── formulaire séparé en bas de page --}}
    <div style="margin-top:2.5rem; padding-top:1.5rem; border-top:1px solid rgba(255,255,255,.08);">
        <form method="POST" action="{{ route('services.destroy', $service) }}" onsubmit="return confirm('Supprimer définitivement « {{ $service->name }} » ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-delete-service">🗑 Supprimer ce service</button>
        </form>
    </div>

</div>

<script>
    /* ── Afficher/masquer mot de passe ── */
    function togglePwd() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    /* ── Jauge d'entropie ── */
    const scoreColors = ['#cc2200', '#e06000', '#e0a000', '#3a9a00', '#00aa00'];
    const scoreLabels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];

    function updateEntropy(value) {
        const bar = document.getElementById('entropy-bar');
        const lbl = document.getElementById('entropy-label');
        if (!value) { bar.style.width = '0%'; lbl.textContent = ''; return; }
        const result = window.zxcvbn(value);
        const score  = result.score;
        bar.style.width      = ((score + 1) * 20) + '%';
        bar.style.background = scoreColors[score];
        lbl.textContent      = scoreLabels[score] + ' — ~' + Math.round(result.guesses_log10 * 3.32) + ' bits';
        lbl.style.color      = scoreColors[score];
    }

    /* ── Pills du générateur ── */
    function togglePill(btn) { btn.classList.toggle('on'); }

    /* ── Calculer l'entropie du mot de passe actuel au chargement ── */
    window.addEventListener('DOMContentLoaded', () => {
        const pwd = document.getElementById('password').value;
        if (pwd) updateEntropy(pwd);

        // Charger les icônes avec fallbacks haute qualité
        document.querySelectorAll('[data-icon-domain]').forEach(wrap => {
            const domain   = wrap.dataset.iconDomain;
            const img      = wrap.querySelector('.svc-icon-img');
            const fallback = wrap.querySelector('.svc-icon-fallback');
            if (!img || !domain) return;
            const sources = [
                `https://logo.clearbit.com/${domain}`,
                `https://${domain}/apple-touch-icon.png`,
                `https://${domain}/apple-touch-icon-precomposed.png`,
                `https://www.google.com/s2/favicons?domain=${domain}&sz=64`,
            ];
            let idx = 0;
            img.onload  = () => { img.style.display = 'block'; if (fallback) fallback.style.display = 'none'; };
            img.onerror = () => {
                idx++;
                if (idx < sources.length) { img.src = sources[idx]; }
                else { img.style.display = 'none'; if (fallback) fallback.style.display = 'block'; }
            };
            img.src = sources[idx];
        });
    });

    /* ── Générateur ── */
    function toggleGenerator() {
        const gen = document.getElementById('generator');
        gen.style.display = gen.style.display === 'none' ? 'block' : 'none';
    }

    function secureRandInt(max) {
        const arr = new Uint32Array(1);
        let val;
        do { window.crypto.getRandomValues(arr); val = arr[0]; }
        while (val >= Math.floor(0xFFFFFFFF / max) * max);
        return val % max;
    }

    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = secureRandInt(i + 1);
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    function generatePassword() {
        const length  = parseInt(document.getElementById('gen-length').value);
        const sets = {
            upper:   { active: document.getElementById('gen-upper').classList.contains('on'),   chars: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' },
            lower:   { active: document.getElementById('gen-lower').classList.contains('on'),   chars: 'abcdefghijklmnopqrstuvwxyz' },
            digits:  { active: document.getElementById('gen-digits').classList.contains('on'),  chars: '0123456789' },
            symbols: { active: document.getElementById('gen-symbols').classList.contains('on'), chars: '!@#$%^&*()-_=+[]{}|;:,.<>?' },
        };

        const activeSets = Object.values(sets).filter(s => s.active);

        if (activeSets.length === 0) {
            ['gen-upper','gen-lower','gen-digits'].forEach(id => document.getElementById(id).classList.add('on'));
            return generatePassword();
        }

        const fullChars = activeSets.map(s => s.chars).join('');
        const result = [];

        activeSets.forEach(set => {
            result.push(set.chars[secureRandInt(set.chars.length)]);
        });

        while (result.length < length) {
            result.push(fullChars[secureRandInt(fullChars.length)]);
        }

        const pwd = shuffle(result).join('');

        const input = document.getElementById('password');
        input.type  = 'text';
        input.value = pwd;
        updateEntropy(pwd);
    }

    /* ── Normalisation URL ── */
    function normalizeUrl(input) {
        const val = input.value.trim();
        if (val && !val.match(/^https?:\/\//i)) {
            input.value = 'https://' + val;
            updateIconFromUrl(input.value);
        }
    }

    /* ── Icône dynamique — fallbacks haute qualité ── */
    let iconDebounce;
    function updateIconFromUrl(url) {
        clearTimeout(iconDebounce);
        iconDebounce = setTimeout(() => {
            try {
                const domain = new URL(url).hostname;
                const img = document.getElementById('icon-img');
                const ltr = document.getElementById('icon-letter');
                const sources = [
                    `https://logo.clearbit.com/${domain}`,
                    `https://${domain}/apple-touch-icon.png`,
                    `https://${domain}/apple-touch-icon-precomposed.png`,
                    `https://www.google.com/s2/favicons?domain=${domain}&sz=64`,
                ];
                let idx = 0;
                img.onload  = () => { img.style.display = 'block'; ltr.style.display = 'none'; };
                img.onerror = () => {
                    idx++;
                    if (idx < sources.length) { img.src = sources[idx]; }
                    else { img.style.display = 'none'; ltr.style.display = 'block'; }
                };
                img.src = sources[idx];
            } catch(e) {}
        }, 500);
    }
</script>

</body>
</html>
