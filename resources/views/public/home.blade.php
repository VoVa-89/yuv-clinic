@extends('layouts.app')
@section('title', 'Главная — '.config('clinic.name').', '.config('clinic.city'))
@section('meta_description', 'Стоматологическая клиника в '.config('clinic.city').'. '.config('clinic.slogan'))
@section('content')
<section class="hero hero--accent" aria-labelledby="hero-title">
    <div class="hero__inner hero__inner--split">
        <div class="hero__media">
            <picture>
                <source srcset="{{ asset('images/hero/uv-dent-logo-main.webp') }}" type="image/webp">
                <img
                    class="hero__image"
                    src="{{ asset('images/hero/uv-dent-logo-main.png') }}"
                    width="640"
                    height="360"
                    alt="{{ config('clinic.name') }} — фирменный знак клиники"
                    loading="eager"
                    decoding="async"
                >
            </picture>
        </div>
        <div class="hero__copy">
            <h1 id="hero-title" class="sr-only">{{ config('clinic.name') }}</h1>
            <p class="hero__lead">{{ config('clinic.slogan') }}</p>
            <p class="hero__tagline">{{ config('clinic.hero_value') }}</p>
            <div class="hero__ctas">
                <a class="btn btn--ghost" href="{{ route('services.index') }}">Услуги и цены</a>
                <a class="btn btn--ghost" href="{{ route('contacts') }}">Контакты</a>
            </div>
        </div>
    </div>
</section>

<section class="home-services-section" aria-labelledby="sec-services">
    <header class="home-services-intro">
        <div class="home-services-intro__text">
            <h2 id="sec-services" class="home-services-intro__title">Основные направления</h2>
            <p class="home-services-intro__lead">Полный спектр стоматологической помощи: от эстетики и профилактики до сложного лечения&nbsp;— по&nbsp;показаниям, после диагностики.</p>
        </div>
        @if(!$services->isEmpty())
            <a class="home-services-intro__cta" href="{{ route('services.index') }}">
                <span class="home-services-intro__cta-label">Все услуги</span>
                <span class="home-services-intro__cta-icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                </span>
            </a>
        @endif
    </header>

    @if($services->isEmpty())
        <p class="home-services-empty">Раздел услуг скоро будет заполнен.</p>
    @else
        <div class="service-showcase-grid">
            @foreach($services as $index => $service)
                @php
                    $variant = ['featured', 'elevated', 'visual'][$index % 3];
                @endphp
                @include('public.partials.service-home-card', ['service' => $service, 'variant' => $variant, 'iconSet' => $index % 3])
            @endforeach
        </div>
    @endif
</section>

<section class="page-head" style="margin-top:2.5rem;" aria-labelledby="sec-doctors">
    <h2 id="sec-doctors" class="page-head__h1" style="font-size:1.35rem;">Врачи</h2>
    <p class="page-head__lead">Команда клиники.</p>
</section>
<div class="card-grid">
    @foreach($doctors as $doctor)
        <article class="card card--person">
            @if($doctor->photo)
                <img class="card__img" src="{{ \Illuminate\Support\Str::startsWith($doctor->photo, ['http://', 'https://']) ? $doctor->photo : asset($doctor->photo) }}" alt="Портрет: {{ $doctor->name }}" width="200" height="200" loading="lazy">
            @endif
            <h3 class="card__title"><a href="{{ route('doctors.show', $doctor) }}">{{ $doctor->name }}</a></h3>
            <p class="card__meta">{{ $doctor->position }}</p>
        </article>
    @endforeach
</div>

@include('public.partials.home-reviews', ['homeReviews' => $homeReviews])

<p style="max-width:72rem; margin:1.5rem auto 0; text-align:center;">
    <a href="{{ route('about') }}">Подробнее о клинике</a> ·
    <a href="{{ route('reviews.index') }}">Отзывы пациентов</a>
</p>
@endsection
