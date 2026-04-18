<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LockKeySecure — Gestionnaire de mots de passe</title>
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

        .card {
            background: rgba(0,0,0,.35);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: var(--radius);
            padding: 3rem 2.5rem;
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(6px);
            text-align: center;
        }

        .logo {
            display: flex; align-items: center; justify-content: center; gap: .65rem;
            margin-bottom: 2rem;
        }
        .logo span { font-size: 1.4rem; font-weight: 700; color: #fff; letter-spacing: .02em; }

        h1 {
            font-size: 1.6rem; font-weight: 700; color: #fff;
            margin-bottom: .65rem; line-height: 1.25;
        }
        p {
            font-size: .95rem; color: rgba(255,255,255,.55);
            line-height: 1.6; margin-bottom: 2.25rem;
        }

        .btns { display: flex; flex-direction: column; gap: .85rem; }

        .btn-primary {
            display: block; padding: .75rem;
            border-radius: var(--radius); border: none;
            background: var(--green-accent); color: #fff;
            font-size: 1rem; font-weight: 700;
            text-decoration: none; cursor: pointer;
            transition: background .2s;
        }
        .btn-primary:hover { background: var(--green-glow); }

        .btn-ghost {
            display: block; padding: .75rem;
            border-radius: var(--radius);
            border: 1px solid rgba(255,255,255,.25);
            background: transparent; color: #fff;
            font-size: 1rem; font-weight: 600;
            text-decoration: none; cursor: pointer;
            transition: background .2s, border-color .2s;
        }
        .btn-ghost:hover { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.45); }
    </style>
</head>
<body>

<div class="card">
    <div class="logo">
        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#00aa00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
        <span>LockKeySecure</span>
    </div>

    <h1>Bienvenue</h1>
    <p>Gérez tous vos mots de passe en toute sécurité, depuis un seul endroit.</p>

    <div class="btns">
        <a href="{{ route('login') }}" class="btn-primary">Se connecter</a>
        <a href="{{ route('register') }}" class="btn-ghost">Créer un compte</a>
    </div>
</div>

</body>
</html>
