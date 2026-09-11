<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Админ — '.config('clinic.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div style="max-width:70rem; margin:0 auto; padding:1rem;">
        <div class="admin-nav">
            <a href="{{ route('admin.dashboard') }}">Панель</a>
            <a href="{{ route('admin.services.index') }}">Услуги</a>
            <a href="{{ route('admin.price-items.index') }}">Прайс</a>
            <a href="{{ route('admin.doctors.index') }}">Врачи</a>
            <a href="{{ route('admin.reviews.index') }}">Отзывы</a>
            <a href="{{ route('admin.documents.index') }}">Документы</a>
            <a href="{{ route('home') }}" target="_blank" rel="noopener">Сайт</a>
            <form method="post" action="{{ route('logout') }}" style="display:inline; margin:0;">@csrf<button type="submit" class="btn btn--primary" style="font-size:0.8rem; padding:0.25rem 0.5rem;">Выход</button></form>
        </div>
        @if(session('status'))
            <div class="alert alert--success">{{ session('status') }}</div>
        @endif
        @include('admin.partials.validation-errors')
        <main class="admin">
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof tinymce === 'undefined') return;
        tinymce.init({
            selector: 'textarea.wys',
            height: 320,
            menubar: false,
            plugins: 'link lists',
            toolbar: 'undo redo | formatselect | bold italic | link bullist numlist | removeformat',
            content_style: 'body { font-family: system-ui, sans-serif; font-size: 14px; }'
        });
    });
    </script>
    @stack('admin_scripts')
</body>
</html>
