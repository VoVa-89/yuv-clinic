@extends('layouts.admin')
@section('title', 'Панель — '.config('clinic.name'))
@section('content')
<h1 class="admin__h1">Админ-панель</h1>
<p>Ожидают модерации отзывов: <strong>{{ $pendingReviews }}</strong></p>
<p>Услуг: {{ $servicesCount }}, врачей: {{ $doctorsCount }}, документов: {{ $documentsCount }}</p>
@endsection
