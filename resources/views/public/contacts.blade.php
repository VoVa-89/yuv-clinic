@extends('layouts.app')
@section('title', 'Контакты — '.config('clinic.name').', '.config('clinic.city'))
@section('meta_description', 'Адрес и контакты '.config('clinic.name').': '.config('clinic.address').'.')
@section('content')
<div class="page-head">
    <h1 class="page-head__h1">Контакты</h1>
    <p class="page-head__lead">Приём по предварительной записи. Уточняйте график по телефону.</p>
</div>

<section class="contacts" aria-labelledby="contacts-heading">
    <h2 id="contacts-heading" class="visually-hidden">Адрес, телефон, время работы и карта</h2>

    <div class="contacts__info">
        <ul class="contacts__list">
            <li class="contacts__item">
                <span class="contacts__icon contacts__icon--address" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                        <path d="M12 21s7-4.35 7-10a7 7 0 10-14 0c0 5.65 7 10 7 10z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="11" r="2.2" stroke="currentColor" stroke-width="1.6"/>
                    </svg>
                </span>
                <div class="contacts__body">
                    <span class="contacts__label">Адрес</span>
                    <span class="contacts__value">{{ config('clinic.address') }}</span>
                </div>
            </li>
            <li class="contacts__item contacts__item--primary">
                <span class="contacts__icon contacts__icon--phone" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                        <path d="M8.5 4h2l1.2 3-1.6 1.6a11 11 0 006.3 6.3L18 13l3 1.2v2a2 2 0 01-2 1.7c-8.5 0-15.4-6.9-15.4-15.4A2 2 0 018.5 4z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div class="contacts__body">
                    <span class="contacts__label">Телефон</span>
                    <a class="contacts__phone" href="tel:{{ preg_replace('/\s+/', '', config('clinic.phone')) }}">{{ config('clinic.phone') }}</a>
                    @if(config('clinic.phone_second'))
                        <a class="contacts__phone" href="tel:{{ preg_replace('/\s+/', '', config('clinic.phone_second')) }}">{{ config('clinic.phone_second') }}</a>
                    @endif
                </div>
            </li>
            <li class="contacts__item">
                <span class="contacts__icon contacts__icon--email" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                        <path d="M4 6h16v12H4V6z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div class="contacts__body">
                    <span class="contacts__label">Email</span>
                    <a class="contacts__value contacts__link" href="mailto:{{ config('clinic.email') }}">{{ config('clinic.email') }}</a>
                </div>
            </li>
            @php $openingHours = config('clinic.opening_hours', []); @endphp
            @if(! empty($openingHours))
            <li class="contacts__item">
                <span class="contacts__icon contacts__icon--hours" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                        <circle cx="12" cy="12" r="8.25" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M12 8v4.25l2.75 1.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div class="contacts__body">
                    <span class="contacts__label">Время работы</span>
                    <ul class="contacts__hours">
                        @foreach($openingHours as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    </ul>
                </div>
            </li>
            @endif
        </ul>
        <p class="contacts__disclaimer not-offer">Информация на сайте носит справочный характер и не заменяет консультацию врача.</p>
    </div>

    @if(config('clinic.map_embed_url') || (config('clinic.entrance_photo') && file_exists(public_path(config('clinic.entrance_photo')))))
        <div class="contacts__visual">
            <div class="contacts__visual-grid">
                @if(config('clinic.map_embed_url'))
                    <div class="contacts__map-frame">
                        <a href="https://yandex.ru/maps/18/petrozavodsk/?utm_medium=mapframe&amp;utm_source=maps" class="contacts__ym-forelink" target="_blank" rel="noopener noreferrer">{{ config('clinic.city') }}</a>
                        <a href="{{ config('clinic.map_external_url') }}" class="contacts__ym-forelink contacts__ym-forelink--2" target="_blank" rel="noopener noreferrer">{{ config('clinic.address') }} — Яндекс&nbsp;Карты</a>
                        <iframe
                            title="Карта: {{ config('clinic.name') }}, {{ config('clinic.address') }}"
                            src="{{ config('clinic.map_embed_url') }}"
                            class="contacts__iframe"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                @endif
                @if(config('clinic.entrance_photo') && file_exists(public_path(config('clinic.entrance_photo'))))
                    <figure class="contacts__entrance">
                        <picture>
                            <source srcset="{{ asset(config('clinic.entrance_photo')) }}" type="image/webp">
                            <img
                                src="{{ asset('images/contacts/entrance.jpg') }}"
                                alt="Вход в клинику {{ config('clinic.name') }}, {{ config('clinic.address') }}"
                                class="contacts__entrance-img"
                                width="1400"
                                height="1050"
                                loading="lazy"
                                decoding="async"
                            >
                        </picture>
                    </figure>
                @endif
            </div>
        </div>
    @endif
</section>
@endsection
