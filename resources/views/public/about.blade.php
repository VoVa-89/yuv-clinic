@extends('layouts.app')
@section('title', 'О клинике — '.config('clinic.name'))
@section('meta_description', 'О '.config('clinic.name').' в '.config('clinic.city').'. Основатели, подход к лечению, оборудование.')
@section('content')
<div class="page-head">
    <h1 class="page-head__h1">О клинике</h1>
    <p class="page-head__lead">«{{ config('clinic.name') }}» — клиника в {{ config('clinic.city') }}, где за пациентом закрепляют опытных врачей и сопровождают на всех этапах лечения.</p>
</div>

<div style="max-width:45rem; margin:0 auto;">
    <h2 class="page-head__h1" style="font-size:1.2rem; margin-top:0;">Юрий и Василий</h2>
    <p>Клиника названа в честь основателей — стоматологов, которым доверяют не только схему лечения, но и человеческое отношение. Мы публикуем прозрачную информацию об услугах и стоимости (п.&nbsp;1 ст.&nbsp;18 ФЗ&nbsp;№&nbsp;323-ФЗ) и работаем в рамках лицензии@if(config('clinic.license_number')) №&nbsp;{{ config('clinic.license_number') }}@if(config('clinic.license_date')) от {{ config('clinic.license_date') }}@endif@if(config('clinic.license_issuer')), выдана {{ config('clinic.license_issuer') }}@endif@endif. Подробности и сканы — в разделе <a href="{{ route('documents') }}">«Лицензии и документы»</a>.</p>
    <p>Наш девиз: <em>{{ config('clinic.slogan') }}</em></p>
</div>

<section class="page-head" style="margin-top:2rem;" aria-labelledby="ab-doc">
    <h2 id="ab-doc" class="page-head__h1" style="font-size:1.35rem;">Врачи</h2>
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
@endsection
