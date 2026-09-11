@extends('layouts.app')
@section('title', 'Цены на наши услуги — '.config('clinic.name').', '.config('clinic.city'))
@section('meta_description', 'Прейскурант основных стоматологических процедур в '.config('clinic.city').'. Точная стоимость — после осмотра.')
@section('content')
<div class="price-page">
    <nav class="price-page__crumbs" aria-label="Навигационная цепочка">
        <a href="{{ route('home') }}">Главная</a>
        <span aria-hidden="true"> — </span>
        <span>Цены</span>
    </nav>

    <div class="page-head" style="margin-bottom: 1rem;">
        <h1 class="page-head__h1">Цены на наши услуги</h1>
        <p class="page-head__lead">Полный спектр стоматологической помощи. Итоговую сумму и план согласуем после диагностики.</p>
    </div>

    <p class="price-page__intro">Актуальность уточняйте в клинике. Наименования процедур приводятся в соответствии с номенклатурой медицинских услуг (Приказ Минздрава РФ №&nbsp;804н) — при заполнении кода в карточке позиции. Перечень не является исчерпывающим и <strong>не является публичной офертой</strong> (ст.&nbsp;437&nbsp;ГК&nbsp;РФ).</p>

    <div class="price-table-wrap">
        <table class="price-table">
            <thead>
                <tr>
                    <th scope="col">Процедура</th>
                    <th scope="col">Стоимость</th>
                </tr>
            </thead>
            @foreach($sectionKeys as $sectionKey)
                @php
                    $rows = ($itemsBySection[$sectionKey] ?? collect());
                @endphp
                @include('public.partials.price-table-section-tbody', [
                    'sectionTitle' => $sectionTitles[$sectionKey],
                    'rows' => $rows,
                ])
            @endforeach
        </table>
    </div>

    <p style="margin-top: 1.5rem; text-align: center;">
        <a href="{{ route('services.index') }}">← Услуги и цены (направления)</a>
    </p>
</div>
@endsection
