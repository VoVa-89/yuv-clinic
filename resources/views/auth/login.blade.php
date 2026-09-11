@extends('layouts.app')
@section('title', 'Вход — '.config('clinic.name'))
@section('content')
<div class="page-head">
    <h1 class="page-head__h1">Вход в админ-панель</h1>
</div>
<div style="max-width:24rem; margin:0 auto;" class="review-form">
    <form method="post" action="{{ route('login.attempt') }}">
        @csrf
        <div class="review-form__row">
            <label for="username">Логин</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" spellcheck="false">
        </div>
        <div class="review-form__row">
            <label for="password">Пароль</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
        </div>
        <div class="review-form__row review-form__agree">
            <input type="checkbox" name="remember" id="remember" value="1" @checked(old('remember'))>
            <label for="remember">Запомнить</label>
        </div>
        @error('username')
            <div class="review-form__error" style="margin-bottom:0.5rem;">{{ $message }}</div>
        @enderror
        <button class="btn btn--primary" type="submit">Войти</button>
    </form>
</div>
@endsection
