@extends('layouts.app')
@section('title', 'Врачи — '.config('clinic.name').', '.config('clinic.city'))
@section('meta_description', 'Врачи '.config('clinic.name').' в '.config('clinic.city').': образование, опыт, направления.')
@section('content')
<div class="page-head">
    <h1 class="page-head__h1">Врачи</h1>
    <p class="page-head__lead">Специалисты клиники с подтверждённой квалификацией.</p>
</div>
<div class="card-grid">
    @foreach($doctors as $doctor)
        <article class="card card--person">
            @if($doctor->photo)
                <img class="card__img" src="{{ \Illuminate\Support\Str::startsWith($doctor->photo, ['http://', 'https://']) ? $doctor->photo : asset($doctor->photo) }}" alt="{{ $doctor->name }}, {{ $doctor->position }}" width="200" height="200" loading="lazy">
            @endif
            <h2 class="card__title"><a href="{{ route('doctors.show', $doctor) }}">{{ $doctor->name }}</a></h2>
            <p class="card__meta">{{ $doctor->position }}</p>
        </article>
    @endforeach
</div>
@endsection
