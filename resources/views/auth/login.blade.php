<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — LockKeySecure</title>
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

        .alert {
            background: rgba(0,170,0,.15);
            border: 1px solid rgba(0,170,0,.4);
            color: #a3ffb0;
            border-radius: var(--radius);
            padding: .75rem 1rem;
            font-size: .875rem;
            margin-bottom: 1.25rem;
        }
        .alert-error {
            background: rgba(200,0,0,.15);
            border-color: rgba(200,0,0,.4);
            color: #ffb0b0;
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

        .remember-row {
            display: flex; align-items: center; gap: .5rem;
            margin-bottom: 1.5rem;
        }
        .remember-row input[type="checkbox"] { accent-color: var(--green-accent); width: 16px; height: 16px; cursor: pointer; }
        .remember-row label { color: rgba(255,255,255,.65); font-size: .875rem; cursor: pointer; }

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

        .forgot-link {
            display: block; text-align: right;
            font-size: .8rem; color: rgba(255,255,255,.5);
            text-decoration: none; margin-top: .3rem;
        }
        .forgot-link:hover { color: #fff; }

        .btn-passkey {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: .6rem;
            padding: .65rem 1rem;
            border-radius: var(--radius);
            border: 1px solid rgba(0,170,0,.4);
            background: rgba(0,170,0,.1);
            color: #a3ffb0;
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, border-color .2s;
            margin-bottom: 1rem;
        }
        .btn-passkey:hover { background: rgba(0,170,0,.2); border-color: var(--green-accent); }
        #passkey-error { color: #ffb0b0; font-size: .8rem; text-align: center; margin-top: .5rem; display: none; }
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
    <h1>Connexion</h1>

    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
            <label for="email">Adresse courriel</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="vous@exemple.com">
            @error('email')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            @error('password')<div class="field-error">{{ $message }}</div>@enderror
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
            @endif
        </div>

        <div class="remember-row">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">Se souvenir de moi</label>
        </div>

        <button type="submit" class="btn-submit">Se connecter</button>
    </form>

    <div class="divider">ou</div>

    <button type="button" class="btn-passkey" id="btn-passkey">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5z"/><path d="M15 12H9a4 4 0 0 0-4 4v2h14v-2a4 4 0 0 0-4-4z"/>
        </svg>
        Se connecter avec Passkey
    </button>
    <div id="passkey-error"></div>

    <div class="divider">ou continuer avec</div>

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
        Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a>
    </p>
</div>

<script>
document.getElementById('btn-passkey').addEventListener('click', async () => {
    const errorEl = document.getElementById('passkey-error');
    errorEl.style.display = 'none';

    try {
        const optRes = await fetch('{{ route('passkeys.auth.options') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({}),
        });
        const options = await optRes.json();

        const challengeBuffer = Uint8Array.from(
            atob(options.challenge.replace(/-/g, '+').replace(/_/g, '/')),
            c => c.charCodeAt(0)
        );

        const assertion = await navigator.credentials.get({
            publicKey: {
                challenge: challengeBuffer,
                rpId: options.rpId,
                allowCredentials: [],
                userVerification: options.userVerification,
                timeout: options.timeout,
            },
        });

        const toBase64url = buf =>
            btoa(String.fromCharCode(...new Uint8Array(buf)))
                .replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');

        const authRes = await fetch('{{ route('passkeys.authenticate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                id:                assertion.id,
                clientDataJSON:    toBase64url(assertion.response.clientDataJSON),
                authenticatorData: toBase64url(assertion.response.authenticatorData),
                signature:         toBase64url(assertion.response.signature),
            }),
        });

        const result = await authRes.json();

        if (result.success) {
            window.location.href = result.redirect;
        } else {
            errorEl.textContent = result.error ?? 'Échec de la connexion par passkey.';
            errorEl.style.display = 'block';
        }
    } catch (e) {
        errorEl.textContent = 'Passkey annulé ou non supporté par ce navigateur.';
        errorEl.style.display = 'block';
    }
});
</script>
</body>
</html>
