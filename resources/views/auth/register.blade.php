<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer un compte — LockKeySecure</title>
    @vite(['resources/js/app.js'])
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
        body {
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, var(--green) 0%, var(--green-mid) 60%, var(--green-light) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .brand {
            display: flex; align-items: center; gap: .6rem;
            text-decoration: none; color: #fff; margin-bottom: 2rem;
        }
        .brand svg { flex-shrink: 0; }
        .brand span { font-size: 1.4rem; font-weight: 700; letter-spacing: .02em; }

        .card {
            background: rgba(0, 0, 0, .35);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: var(--radius);
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 440px;
            backdrop-filter: blur(6px);
        }
        .card h1 { color: #fff; font-size: 1.5rem; font-weight: 700; margin-bottom: 1.75rem; text-align: center; }

        .alert-error {
            background: rgba(200,0,0,.15);
            border: 1px solid rgba(200,0,0,.4);
            color: #ffb0b0;
            border-radius: var(--radius);
            padding: .75rem 1rem;
            font-size: .875rem;
            margin-bottom: 1.25rem;
        }

        .field { margin-bottom: 1.1rem; }
        .field label { display: block; color: rgba(255,255,255,.75); font-size: .875rem; margin-bottom: .4rem; }
        .field input {
            width: 100%;
            padding: .6rem .85rem;
            border-radius: var(--radius);
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(255,255,255,.08);
            color: #fff;
            font-size: .95rem;
            outline: none;
            transition: border-color .2s;
        }
        .field input::placeholder { color: rgba(255,255,255,.35); }
        .field input:focus { border-color: var(--green-accent); }
        .field-error { color: #ffb0b0; font-size: .8rem; margin-top: .3rem; }

        .btn-submit {
            width: 100%;
            padding: .7rem;
            border-radius: var(--radius);
            border: none;
            background: var(--green-accent);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: .5rem;
            transition: background .2s;
        }
        .btn-submit:hover { background: var(--green-glow); }

        .divider {
            display: flex; align-items: center; gap: .75rem;
            margin: 1.5rem 0;
            color: rgba(255,255,255,.35); font-size: .8rem;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1;
            height: 1px; background: rgba(255,255,255,.15);
        }

        .oauth-btns { display: flex; flex-direction: column; gap: .75rem; }
        .btn-oauth {
            display: flex; align-items: center; justify-content: center; gap: .6rem;
            padding: .65rem 1rem;
            border-radius: var(--radius);
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(255,255,255,.07);
            color: #fff;
            font-size: .875rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s, border-color .2s;
        }
        .btn-oauth:hover { background: rgba(255,255,255,.15); border-color: rgba(255,255,255,.35); }
        .btn-oauth svg { flex-shrink: 0; }

        .footer-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: .875rem;
            color: rgba(255,255,255,.5);
        }
        .footer-link a { color: var(--green-accent); text-decoration: none; }
        .footer-link a:hover { text-decoration: underline; }

        .hint { color: rgba(255,255,255,.35); font-size: .78rem; margin-top: .3rem; }

        .entropy-wrap { margin-top: .5rem; }
        .entropy-bar-bg {
            height: 6px; background: rgba(255,255,255,.12);
            border-radius: 99px; overflow: hidden;
        }
        .entropy-bar { height: 100%; width: 0%; border-radius: 99px; transition: width .3s, background .3s; }
        .entropy-label { font-size: .78rem; margin-top: .3rem; color: rgba(255,255,255,.35); }

        .pwd-wrap { position: relative; }
        .pwd-wrap input { padding-right: 2.8rem; }
        .pwd-toggle {
            position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: rgba(255,255,255,.4);
            cursor: pointer; display: flex; align-items: center; transition: color .2s;
        }
        .pwd-toggle:hover { color: var(--green-accent); }

        .gen-toggle-btn {
            background: none; border: 1px solid rgba(255,255,255,.2);
            color: rgba(255,255,255,.6); border-radius: var(--radius);
            padding: .3rem .8rem; font-size: .78rem; cursor: pointer;
            margin-top: .5rem; transition: all .2s; font-family: Arial, sans-serif;
        }
        .gen-toggle-btn:hover { background: rgba(255,255,255,.08); color: #fff; }

        .generator {
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12);
            border-radius: var(--radius); padding: 1rem; margin-top: .6rem;
        }
        .gen-row { display: flex; align-items: center; gap: .75rem; margin-bottom: .75rem; }
        .gen-row label { font-size: .8rem; color: rgba(255,255,255,.6); white-space: nowrap; }
        .gen-row input[type="range"] { flex: 1; accent-color: var(--green-accent); cursor: pointer; }
        .gen-length-val { font-size: .85rem; font-weight: 700; color: var(--green-accent); min-width: 24px; text-align: right; }

        .gen-pills { display: flex; gap: .4rem; flex-wrap: wrap; margin-bottom: .75rem; }
        .gen-pill {
            padding: .25rem .75rem; border-radius: 99px;
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
    </style>
</head>
<body>

<a href="/" class="brand">
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#00aa00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
    </svg>
    <span>LockKeySecure</span>
</a>

<div class="card">
    <h1>Créer un compte</h1>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="field">
            <label for="name">Nom d'utilisateur</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Jean Dupont">
            @error('name')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="email">Adresse courriel</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="vous@exemple.com">
            @error('email')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="password">Mot de passe</label>
            <div class="pwd-wrap">
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" oninput="updateEntropy(this.value)">
                <button type="button" class="pwd-toggle" onclick="togglePwd()" title="Afficher/masquer">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <div class="entropy-wrap">
                <div class="entropy-bar-bg"><div class="entropy-bar" id="entropy-bar"></div></div>
                <div class="entropy-label" id="entropy-label">Minimum 12 caractères</div>
            </div>
            <button type="button" class="gen-toggle-btn" onclick="toggleGenerator()">⚙ Générer un mot de passe</button>
            <div class="generator" id="generator" style="display:none;">
                <div class="gen-row">
                    <label for="gen-length">Longueur :</label>
                    <input type="range" id="gen-length" min="12" max="64" value="20" oninput="document.getElementById('gen-length-val').textContent = this.value">
                    <span class="gen-length-val" id="gen-length-val">20</span>
                </div>
                <div class="gen-pills">
                    <button type="button" class="gen-pill on" id="gen-upper"   onclick="togglePill(this)">A–Z Maj</button>
                    <button type="button" class="gen-pill on" id="gen-lower"   onclick="togglePill(this)">a–z Min</button>
                    <button type="button" class="gen-pill on" id="gen-digits"  onclick="togglePill(this)">0–9 Chiffres</button>
                    <button type="button" class="gen-pill"    id="gen-symbols" onclick="togglePill(this)">!@# Symboles</button>
                </div>
                <button type="button" class="btn-generate" onclick="generatePassword()">↻ Générer</button>
            </div>
            @error('password')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
            @error('password_confirmation')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn-submit">Créer mon compte</button>
    </form>

    <div class="divider">ou s'inscrire avec</div>

    <div class="oauth-btns">
        <a href="{{ route('oauth.redirect', 'google') }}" class="btn-oauth">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Continuer avec Google
        </a>

        <a href="{{ route('oauth.redirect', 'github') }}" class="btn-oauth">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.385-1.335-1.755-1.335-1.755-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
            </svg>
            Continuer avec GitHub
        </a>

        <a href="{{ route('oauth.redirect', 'discord') }}" class="btn-oauth">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="#5865F2" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/>
            </svg>
            Continuer avec Discord
        </a>
    </div>

    <p class="footer-link">
        Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
    </p>
</div>

<script>
    const scoreColors = ['#cc2200', '#e06000', '#e0a000', '#3a9a00', '#00aa00'];
    const scoreLabels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];

    function updateEntropy(value) {
        const bar = document.getElementById('entropy-bar');
        const lbl = document.getElementById('entropy-label');

        if (!value) {
            bar.style.width      = '0%';
            bar.style.background = 'transparent';
            lbl.textContent      = 'Minimum 12 caractères';
            lbl.style.color      = 'rgba(255,255,255,.35)';
            return;
        }

        const result = window.zxcvbn(value);
        const score  = result.score;
        bar.style.width      = ((score + 1) * 20) + '%';
        bar.style.background = scoreColors[score];
        lbl.textContent      = scoreLabels[score] + ' — ~' + Math.round(result.guesses_log10 * 3.32) + ' bits';
        lbl.style.color      = scoreColors[score];
    }

    function togglePwd() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    }

    function toggleGenerator() {
        const gen = document.getElementById('generator');
        gen.style.display = gen.style.display === 'none' ? 'block' : 'none';
    }

    function togglePill(btn) {
        btn.classList.toggle('on');
    }

    function secureRandInt(max) {
        const arr = new Uint32Array(1); let val;
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
        const length = parseInt(document.getElementById('gen-length').value);
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
        activeSets.forEach(set => { result.push(set.chars[secureRandInt(set.chars.length)]); });
        while (result.length < length) { result.push(fullChars[secureRandInt(fullChars.length)]); }
        const pwd = shuffle(result).join('');
        const input = document.getElementById('password');
        input.type = 'text';
        input.value = pwd;
        const confirm = document.getElementById('password_confirmation');
        confirm.value = pwd;
        updateEntropy(pwd);
    }
</script>
</body>
</html>
