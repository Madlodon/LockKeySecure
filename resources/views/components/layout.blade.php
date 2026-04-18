@php
    use Illuminate\Support\Facades\Route;
@endphp
@php($route = Route::current()->uri())

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <link rel="stylesheet" href="{{ asset("stylesheets/vendors/bootstrap-5.3.8-dist/bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ asset("stylesheets/components/layout.css") }}">
    <link rel="stylesheet" href="{{ asset("stylesheets/components/logo.css") }}">

    @stack("stylesheets")

    <script src="{{ asset("scripts/vendors/bootstrap-5.3.8-dist/bootstrap.bundle.min.js") }}"></script>

    @stack("scripts")
</head>
<body>
<header>
    <x-logo/>
    <div>
        <ul>
            <li><a href="/" @class(['active' => request()->is('/')])>Accueil</a></li>
            <li><a href="/publications" @class(['active' => request()->is('publications*')])>Articles</a></li>
            <li><a href="/publications/create" @class(['active' => request()->is('publications/create')])>Écrire</a></li>

            @if(session('user_id'))
                <li>
                    <a href="/profileEdit" @class(['active' => request()->is('profileEdit')])>
                        <img src="{{ asset(session('user_photo') ?? 'images/default.png') }}"
                             alt="Profil" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                    </a>
                </li>

                <li class="icon">
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer; color: inherit;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m16 17 5-5-5-5"/>
                                <path d="M21 12H9"/>
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            </svg>
                        </button>
                    </form>
                </li>
            @else
                <li>
                    <a href="/login" @class(['active' => request()->is('login') || request()->is('register')])>Connexion</a>
                </li>
            @endif
        </ul>
    </div>
</header>
<main>
    {{ $slot }}
</main>
<footer>
    <x-logo/>
    <p>Tous droits réservés</p>
</footer>
</body>
</html>
