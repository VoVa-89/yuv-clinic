@extends('layouts.app')
@section('title', 'Услуги и цены — '.config('clinic.name').', '.config('clinic.city'))
@section('meta_description', 'Перечень стоматологических услуг и ориентиры по ценам. '.config('clinic.city').'.')
@section('content')
<div class="page-head">
    <h1 class="page-head__h1">Услуги и цены</h1>
    <p class="page-head__lead">Актуальные цены уточняйте в клинике. Перечень услуг — по применению в зависимости от показаний.</p>
</div>
<div class="services-page-actions">
    <a class="btn btn--primary" href="{{ route('prices.index') }}">Цены на наши услуги</a>
</div>
<p class="not-offer" style="max-width:45rem; margin:0 auto 1.5rem;">Информация о ценах <strong>не является публичной офертой</strong> (ст.&nbsp;437 ГК РФ).</p>

<section class="home-services-section" aria-label="Перечень услуг и цен">
    <div class="service-showcase-grid">
        @foreach($services as $index => $service)
            @php
                $variant = ['featured', 'elevated', 'visual'][$index % 3];
            @endphp
            @include('public.partials.service-home-card', [
                'service' => $service,
                'variant' => $variant,
                'iconSet' => $index % 3,
                'showPrice' => true,
            ])
        @endforeach
    </div>
</section>
<aside class="treatment-note" role="note">
    <span class="treatment-note__icon" aria-hidden="true">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4M12 8h.01M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10Z"/>
        </svg>
    </span>
    <p class="treatment-note__text">Решение о плане лечения — после диагностики.</p>
</aside>
@endsection
