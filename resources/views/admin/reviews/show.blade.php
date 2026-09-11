@extends('layouts.admin')
@section('title', 'Отзыв #'.$review->id)
@section('content')
@php
    use App\Models\Review;
    $listQuery = array_filter(request()->only(['status', 'doctor_id']), static fn ($v) => $v !== null && $v !== '');
    $patchQuery = array_filter(['doctor_id' => request('doctor_id')], static fn ($v) => $v !== null && $v !== '');
@endphp
<p><a href="{{ route('admin.reviews.index', $listQuery) }}">← К списку отзывов</a></p>

<h2 class="admin__h1" style="font-size:1.25rem;">Отзыв #{{ $review->id }}</h2>

@include('admin.partials.validation-errors')

<p>
    <strong>Врач:</strong>
    @if($review->doctor)
        <a href="{{ route('admin.doctors.edit', $review->doctor) }}">{{ $review->doctor->name }}</a>
    @else
        —
    @endif
    <br>
    <strong>Имя автора:</strong> {{ e($review->name) }}<br>
    <strong>Оценка:</strong> {{ $review->rating }}/5<br>
    <strong>Дата:</strong> {{ $review->created_at->format('d.m.Y H:i') }}<br>
    <strong>IP:</strong> {{ e($review->ip ?? '—') }}<br>
    <strong>Статус:</strong> {{ Review::statusLabel($review->status) }}
</p>
<p class="admin-review-show-body">{{ $review->body }}</p>

<form method="post" action="{{ route('admin.reviews.status', ['review' => $review] + $patchQuery) }}">
    @csrf
    @method('PATCH')
    <p>
        <label>
            Изменить статус:
            <select name="status" class="form-control" style="max-width:16rem; margin-top:0.35rem;">
                <option value="{{ Review::STATUS_PENDING }}" @selected($review->status === Review::STATUS_PENDING)>{{ Review::statusLabel(Review::STATUS_PENDING) }}</option>
                <option value="{{ Review::STATUS_APPROVED }}" @selected($review->status === Review::STATUS_APPROVED)>{{ Review::statusLabel(Review::STATUS_APPROVED) }}</option>
                <option value="{{ Review::STATUS_REJECTED }}" @selected($review->status === Review::STATUS_REJECTED)>{{ Review::statusLabel(Review::STATUS_REJECTED) }}</option>
            </select>
        </label>
        <button class="btn btn--primary" type="submit" style="margin-top:0.5rem;">Сохранить статус</button>
    </p>
</form>

<hr style="margin:1.5rem 0; border:none; border-top:1px solid #e2e8f0;">

<form method="post" action="{{ route('admin.reviews.destroy', ['review' => $review] + $listQuery) }}" onsubmit="return confirm('Удалить этот отзыв безвозвратно?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn" style="border-color:#c62828; color:#c62828;">Удалить отзыв</button>
</form>
@endsection
