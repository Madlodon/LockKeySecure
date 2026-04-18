<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — LockKeySecure</title>
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
            --topbar-h:     64px;
        }
        body { font-family: Arial, sans-serif; background: #0d1f0d; color: #fff; min-height: 100vh; }

        /* ── TOPBAR ── */
        .topbar {
            height: var(--topbar-h); background: var(--green);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,.4);
        }
        .brand { display: flex; align-items: center; gap: .6rem; text-decoration: none; color: #fff; }
        .brand span { font-size: 1.1rem; font-weight: 700; }
        .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .topbar-user { color: rgba(255,255,255,.6); font-size: .875rem; }
        .btn-logout {
            background: none; border: 1px solid rgba(255,255,255,.25);
            color: rgba(255,255,255,.7); border-radius: var(--radius);
            padding: .35rem .9rem; cursor: pointer; font-size: .8rem;
            transition: all .2s;
        }
        .btn-logout:hover { background: rgba(255,255,255,.1); color: #fff; }

        /* ── LAYOUT ── */
        .page { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* ── HEADER ROW ── */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem; }
        .page-header h1 { font-size: 1.5rem; font-weight: 700; color: #fff; }
        .page-header p { color: rgba(255,255,255,.45); font-size: .875rem; margin-top: .2rem; }

        .btn-add {
            display: flex; align-items: center; gap: .5rem;
            background: var(--green-accent); color: #fff;
            border: none; border-radius: var(--radius);
            padding: .6rem 1.2rem; font-size: .9rem; font-weight: 700;
            text-decoration: none; cursor: pointer; transition: background .2s;
            white-space: nowrap;
        }
        .btn-add:hover { background: var(--green-glow); }

        /* ── FLASH ── */
        .flash {
            background: rgba(0,170,0,.15); border: 1px solid rgba(0,170,0,.35);
            color: #a3ffb0; border-radius: var(--radius);
            padding: .75rem 1rem; font-size: .875rem; margin-bottom: 1.5rem;
        }

        /* ── SEARCH ── */
        .search-wrap { margin-bottom: 1.5rem; }
        .search-wrap input {
            width: 100%; max-width: 400px;
            padding: .6rem 1rem; border-radius: var(--radius);
            border: 1px solid rgba(255,255,255,.15);
            background: rgba(255,255,255,.07); color: #fff; font-size: .9rem;
            outline: none; transition: border-color .2s;
        }
        .search-wrap input::placeholder { color: rgba(255,255,255,.3); }
        .search-wrap input:focus { border-color: var(--green-accent); }

        /* ── GRID ── */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; }

        /* ── CARD ── */
        .card {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: var(--radius);
            padding: 1.25rem 1.25rem 1rem;
            transition: border-color .2s, background .2s;
        }
        .card:hover { border-color: rgba(0,170,0,.4); background: rgba(255,255,255,.08); }

        .card-header { display: flex; align-items: center; gap: .85rem; margin-bottom: .85rem; }
        .card-icon {
            width: 42px; height: 42px; border-radius: 8px;
            background: rgba(255,255,255,.1); overflow: hidden;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .card-icon img { width: 100%; height: 100%; object-fit: contain; }
        .card-icon-fallback { font-size: 1.3rem; font-weight: 700; color: var(--green-accent); display: none; }
        .card-name { font-weight: 700; font-size: 1rem; color: #fff; }
        .card-url { font-size: .78rem; color: rgba(255,255,255,.4); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }

        .card-field { margin-bottom: .6rem; }
        .card-label { font-size: .72rem; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .15rem; }
        .card-value { font-size: .875rem; color: rgba(255,255,255,.8); word-break: break-all; }

        .password-row { display: flex; align-items: center; gap: .5rem; }
        .password-text { font-family: monospace; font-size: .875rem; color: rgba(255,255,255,.8); flex: 1; word-break: break-all; }
        .btn-icon {
            background: none; border: none; color: rgba(255,255,255,.4);
            cursor: pointer; padding: .2rem; border-radius: 4px;
            transition: color .2s; display: flex; align-items: center; flex-shrink: 0;
        }
        .btn-icon:hover { color: var(--green-accent); }

        .card-notes {
            font-size: .8rem; color: rgba(255,255,255,.45);
            border-top: 1px solid rgba(255,255,255,.08);
            margin-top: .75rem; padding-top: .75rem;
            line-height: 1.5; white-space: pre-wrap; word-break: break-word;
            max-height: 80px; overflow: hidden;
        }

        .card-actions { display: flex; gap: .5rem; margin-top: 1rem; }
        .btn-edit {
            flex: 1; padding: .45rem; border-radius: var(--radius);
            border: 1px solid rgba(255,255,255,.2); background: transparent;
            color: rgba(255,255,255,.7); font-size: .8rem; text-align: center;
            text-decoration: none; cursor: pointer; transition: all .2s; display: block;
        }
        .btn-edit:hover { background: rgba(255,255,255,.1); color: #fff; }
        .btn-delete {
            flex: 1; width: 100%; padding: .45rem; border-radius: var(--radius);
            border: 1px solid rgba(200,0,0,.35); background: transparent;
            color: rgba(255,100,100,.7); font-size: .8rem; text-align: center;
            cursor: pointer; transition: all .2s; font-family: Arial, sans-serif;
        }
        .btn-delete:hover { background: rgba(200,0,0,.2); color: #ff8080; }

        /* ── EMPTY ── */
        .empty { text-align: center; padding: 4rem 2rem; color: rgba(255,255,255,.3); }
        .empty > svg { opacity: .2; display: block; margin: 0 auto 1rem; }
        .empty p { margin-bottom: 1.5rem; }

        /* ── TOAST ── */
        #toast {
            position: fixed; bottom: 1.5rem; right: 1.5rem;
            background: var(--green-accent); color: #fff;
            padding: .6rem 1.2rem; border-radius: 8px;
            font-size: .875rem; z-index: 9999;
            opacity: 0; transition: opacity .3s; pointer-events: none;
        }
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
    <div class="topbar-right">
        <span class="topbar-user">{{ auth()->user()->name }}</span>
        <a href="{{ route('settings') }}" class="btn-logout" style="text-decoration:none;display:flex;align-items:center;gap:.35rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            Paramètres
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Déconnexion</button>
        </form>
    </div>
</nav>

<div class="page">

    @if (session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    <div class="page-header">
        <div>
            <h1>Mes services</h1>
            <p>{{ $services->count() }} service{{ $services->count() !== 1 ? 's' : '' }} enregistré{{ $services->count() !== 1 ? 's' : '' }}</p>
        </div>
        <a href="{{ route('services.create') }}" class="btn-add">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Ajouter un service
        </a>
    </div>

    @if ($services->isNotEmpty())
        <div class="search-wrap">
            <input type="search" id="search" placeholder="Rechercher un service..." oninput="filterCards(this.value)">
        </div>

        <div class="grid" id="cards-grid">
            @foreach ($services as $service)
                <div class="card" data-name="{{ strtolower($service->name) }}">
                    <div class="card-header">
                        <div class="card-icon"
                             @if($service->url) data-icon-domain="{{ parse_url($service->url, PHP_URL_HOST) }}" @endif>
                            <img class="svc-icon-img" src="" alt="{{ $service->name }}" style="display:none;width:100%;height:100%;object-fit:contain;">
                            <div class="card-icon-fallback svc-icon-fallback" @if($service->url) style="display:none;" @endif>
                                {{ strtoupper(substr($service->name, 0, 1)) }}
                            </div>
                        </div>
                        <div style="overflow:hidden;">
                            <div class="card-name">{{ $service->name }}</div>
                            @if ($service->url)
                                <div class="card-url" title="{{ $service->url }}">{{ $service->url }}</div>
                            @endif
                        </div>
                    </div>

                    @if ($service->account_id)
                        <div class="card-field">
                            <div class="card-label">Identifiant</div>
                            <div class="card-value">{{ $service->account_id }}</div>
                        </div>
                    @endif

                    <div class="card-field">
                        <div class="card-label">Mot de passe</div>
                        <div class="password-row">
                            <span class="password-text" id="pwd-{{ $service->id }}" data-plain="{{ $service->password }}" data-hidden="1">••••••••••••</span>
                            <button class="btn-icon" onclick="togglePassword({{ $service->id }})" title="Afficher / Masquer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            <button class="btn-icon" onclick="copyText(this.closest('.card').querySelector('.password-text').dataset.plain)" title="Copier">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            </button>
                        </div>
                    </div>

                    @if ($service->notes)
                        <div class="card-notes">{{ $service->notes }}</div>
                    @endif

                    <div class="card-actions">
                        <a href="{{ route('services.edit', $service) }}" class="btn-edit">✏ Modifier</a>
                        <form method="POST" action="{{ route('services.destroy', $service) }}" style="flex:1;" onsubmit="return confirm('Supprimer « {{ $service->name }} » ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">🗑 Supprimer</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#00aa00" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <p>Aucun service enregistré pour l'instant.</p>
            <a href="{{ route('services.create') }}" class="btn-add" style="display:inline-flex;padding:.75rem 1.6rem;font-size:1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Ajouter mon premier service
            </a>
        </div>
    @endif

</div>

<div id="toast">Copié !</div>

<script>
    function togglePassword(id) {
        const el = document.getElementById('pwd-' + id);
        if (el.dataset.hidden === '1') {
            el.textContent = el.dataset.plain;
            el.dataset.hidden = '0';
        } else {
            el.textContent = '••••••••••••';
            el.dataset.hidden = '1';
        }
    }

    function copyText(text) {
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.getElementById('toast');
            toast.style.opacity = '1';
            setTimeout(() => { toast.style.opacity = '0'; }, 1800);
        });
    }

    function filterCards(query) {
        document.querySelectorAll('#cards-grid .card').forEach(card => {
            card.style.display = card.dataset.name.includes(query.toLowerCase()) ? '' : 'none';
        });
    }

    /* ── Chargement des icônes avec fallbacks haute qualité ── */
    function loadIconWithFallback(img, fallbackEl, domain) {
        const sources = [
            `https://logo.clearbit.com/${domain}`,
            `https://${domain}/apple-touch-icon.png`,
            `https://${domain}/apple-touch-icon-precomposed.png`,
            `https://www.google.com/s2/favicons?domain=${domain}&sz=64`,
        ];
        let idx = 0;
        img.onload  = () => { img.style.display = 'block'; fallbackEl.style.display = 'none'; };
        img.onerror = () => {
            idx++;
            if (idx < sources.length) { img.src = sources[idx]; }
            else { img.style.display = 'none'; fallbackEl.style.display = 'flex'; }
        };
        img.src = sources[idx];
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-icon-domain]').forEach(wrap => {
            const domain   = wrap.dataset.iconDomain;
            const img      = wrap.querySelector('.svc-icon-img');
            const fallback = wrap.querySelector('.svc-icon-fallback');
            if (img && domain) loadIconWithFallback(img, fallback, domain);
        });
    });
</script>

</body>
</html>
