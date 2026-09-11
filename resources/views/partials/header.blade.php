<header class="header site__header" role="banner">
    <div class="header__inner">
        <a class="header__brand" href="{{ route('home') }}">
            @if(file_exists(public_path('images/logo.png')) || file_exists(public_path('images/logo.svg')))
                <img class="header__logo" src="{{ file_exists(public_path('images/logo.svg')) ? asset('images/logo.svg') : asset('images/logo.png') }}" width="52" height="52" alt="{{ config('clinic.name') }}">
            @else
                <span class="header__logo header__logo--placeholder" aria-hidden="true">Ю.В.</span>
            @endif
            <div class="header__titles">
                <p class="header__kicker">{{ config('clinic.brand_kicker') }}</p>
                <p class="header__title">{{ config('clinic.name_short') }}</p>
                <p class="header__slogan">{{ config('clinic.slogan') }}</p>
            </div>
        </a>
        <nav class="header__nav" aria-label="Основное меню">
            <ul class="nav">
                <li><a class="nav__link {{ request()->routeIs('home') ? 'nav__link--active' : '' }}" href="{{ route('home') }}">Главная</a></li>
                <li><a class="nav__link {{ request()->routeIs('about') ? 'nav__link--active' : '' }}" href="{{ route('about') }}">О клинике</a></li>
                <li><a class="nav__link {{ request()->routeIs('services.*') ? 'nav__link--active' : '' }}" href="{{ route('services.index') }}">Услуги и цены</a></li>
                <li><a class="nav__link {{ request()->routeIs('reviews.*') ? 'nav__link--active' : '' }}" href="{{ route('reviews.index') }}">Отзывы</a></li>
                <li><a class="nav__link {{ request()->routeIs('contacts') ? 'nav__link--active' : '' }}" href="{{ route('contacts') }}">Контакты</a></li>
            </ul>
        </nav>
    </div>
</header>
