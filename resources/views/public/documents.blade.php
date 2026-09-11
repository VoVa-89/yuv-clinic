@extends('layouts.app')
@section('title', 'Лицензии и документы — '.config('clinic.name'))
@section('meta_description', 'Лицензия и документы '.config('clinic.name').', '.config('clinic.city').'.')
@section('content')
<div class="documents-page">
    <nav class="documents-page__breadcrumbs" aria-label="Хлебные крошки">
        <a href="{{ route('home') }}">Главная</a>
        <span class="documents-page__bc-sep" aria-hidden="true">/</span>
        <span aria-current="page">Лицензии и документы</span>
    </nav>

    <header class="page-head">
        <h1 class="page-head__h1">Лицензии и документы</h1>
        <p class="page-head__lead">Официальные сведения и документы клиники.</p>
    </header>

    @if(config('clinic.legal_name') || config('clinic.ogrn') || config('clinic.inn') || config('clinic.license_number'))
        <section class="documents-page__legal" aria-labelledby="docs-legal-heading">
            <h2 id="docs-legal-heading" class="documents-page__legal-title">Сведения об организации</h2>
            <ul class="documents-page__legal-list">
                @if(config('clinic.legal_name'))
                    <li><span class="documents-page__legal-label">Наименование:</span> {{ config('clinic.legal_name') }}</li>
                @endif
                @if(config('clinic.ogrn'))
                    <li><span class="documents-page__legal-label">{{ config('clinic.ogrn_label') }}:</span> {{ config('clinic.ogrn') }}</li>
                @endif
                @if(config('clinic.inn'))
                    <li><span class="documents-page__legal-label">ИНН:</span> {{ config('clinic.inn') }}</li>
                @endif
                @if(config('clinic.legal_address') || config('clinic.address'))
                    <li><span class="documents-page__legal-label">Адрес:</span> {{ config('clinic.legal_address') ?: config('clinic.address') }}</li>
                @endif
                @if(config('clinic.license_number'))
                    <li>
                        <span class="documents-page__legal-label">Лицензия:</span>
                        № {{ config('clinic.license_number') }}
                        @if(config('clinic.license_date'))
                            от {{ config('clinic.license_date') }}
                        @endif
                        @if(config('clinic.license_issuer'))
                            , выдана {{ config('clinic.license_issuer') }}
                        @endif
                    </li>
                @endif
            </ul>
        </section>
    @endif

    @forelse($documents as $doc)
        @if($loop->first)
            <ul class="documents-page__list">
        @endif
        <li>
            <a class="documents-page__card" href="{{ $doc->publicStorageHref() }}" target="_blank" rel="noopener">
                <span class="documents-page__card-main">
                    <span class="documents-page__card-title">{{ $doc->title }}</span>
                    @if(($kind = $doc->kindLabelFromMime()) !== '')
                        <span class="documents-page__card-meta">{{ $kind }}</span>
                    @endif
                </span>
                <span class="documents-page__card-arrow" aria-hidden="true">→</span>
            </a>
        </li>
        @if($loop->last)
            </ul>
        @endif
    @empty
        <p class="documents-page__empty">Сканы лицензии и иные документы появятся здесь после загрузки в админ-панели.</p>
    @endforelse
</div>
@endsection
