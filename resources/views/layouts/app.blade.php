<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('clinic.name').' — '.config('clinic.city'))</title>
    <meta name="description" content="@yield('meta_description', 'Стоматология в '.config('clinic.city').'. '.config('clinic.slogan'))">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @if(config('clinic.gtm_id'))
    <script>
        window.dataLayer = window.dataLayer || [];
        window.__yuvGtmId = @json(config('clinic.gtm_id'));
        window.__yuvLoadGtm = function () {
            if (window.__yuvGtmLoaded || !window.__yuvGtmId) {
                return;
            }
            window.__yuvGtmLoaded = true;
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer', window.__yuvGtmId);
        };
        try {
            if (localStorage.getItem('yuv_cookie_consent') === '1') {
                window.__yuvLoadGtm();
            }
        } catch (e) {}
    </script>
    @endif
    @yield('head_extra')
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @stack('styles')
    @php
        $clinic = [
            '@context' => 'https://schema.org',
            '@type' => 'Dentist',
            'name' => config('clinic.name'),
            'description' => config('clinic.slogan'),
            'url' => url('/'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => config('clinic.city'),
                'streetAddress' => 'ул. Суворова, д. 1',
                'addressCountry' => 'RU',
            ],
            'telephone' => array_values(array_filter([
                config('clinic.phone'),
                config('clinic.phone_second'),
            ])),
        ];
        if (config('clinic.license_number')) {
            $clinic['medicalSpecialty'] = 'Dentistry';
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($clinic, JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('jsonld')
</head>
<body>
    @if(config('clinic.gtm_id'))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('clinic.gtm_id') }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    <div class="site">
        @include('partials.header')
        <main class="site__main" id="content" role="main">
            @yield('content')
        </main>
        @include('partials.footer')
    </div>
    @if(config('clinic.gtm_id'))
        @include('partials.cookie-consent')
    @endif
    @stack('scripts')
</body>
</html>
