@extends('layouts.app')
@section('title', ($doctor->meta_title ?: $doctor->name).' — '.config('clinic.name'))
@section('meta_description', $doctor->meta_description ?: $doctor->position)
@section('head_extra')
@php
    $docLd = [
        '@context' => 'https://schema.org',
        '@type' => 'Physician',
        'name' => $doctor->name,
        'medicalSpecialty' => $doctor->position,
        'url' => url()->current(),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($docLd, JSON_UNESCAPED_UNICODE) !!}</script>
@endsection
@section('content')
<article>
    <div class="page-head">
        <h1 class="page-head__h1">{{ $doctor->name }}</h1>
        <p class="page-head__lead">{{ $doctor->position }}</p>
    </div>
    <div class="doctor-page__grid" style="max-width:50rem; margin:0 auto; display:grid; gap:1.5rem; grid-template-columns:1fr;">
        @if($doctor->photo)
            <div>
                <img src="{{ \Illuminate\Support\Str::startsWith($doctor->photo, ['http://', 'https://']) ? $doctor->photo : asset($doctor->photo) }}" alt="Портрет: {{ $doctor->name }}" style="width:100%; max-width:240px; border-radius:8px;" loading="lazy" width="240" height="300">
            </div>
        @endif
        <div class="prose">
            {!! $doctor->bio !!}
        </div>
    </div>
    @if($doctor->services->isNotEmpty())
        <div style="max-width:50rem; margin:1.5rem auto 0;">
            <h2 style="font-size:1.1rem;">Услуги</h2>
            <ul>
                @foreach($doctor->services as $s)
                    <li><a href="{{ route('services.show', $s) }}">{{ $s->title }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif
    <p style="text-align:center; margin-top:1.5rem;"><a href="{{ route('doctors.index') }}">← Все врачи</a></p>
</article>
@endsection
