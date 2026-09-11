@extends('layouts.admin')
@section('title', 'Отзывы')
@section('content')
<h1 class="admin__h1">Отзывы</h1>

@php
    use App\Models\Review;
    $tabQuery = array_filter(['doctor_id' => request('doctor_id')], static fn ($v) => $v !== null && $v !== '');
    $reviewQuery = request()->only(['status', 'doctor_id']);
    $reviewQuery = array_filter($reviewQuery, static fn ($v) => $v !== null && $v !== '');
    $patchQuery = array_filter(['doctor_id' => request('doctor_id')], static fn ($v) => $v !== null && $v !== '');
@endphp

<nav class="admin-review-tabs" aria-label="Фильтр по статусу">
    @foreach([
        'all' => 'Все',
        Review::STATUS_PENDING => 'На модерации',
        Review::STATUS_APPROVED => 'Одобренные',
        Review::STATUS_REJECTED => 'Отклонённые',
    ] as $tabKey => $tabLabel)
        @php
            $tabHrefParams = $tabQuery;
            if ($tabKey !== 'all') {
                $tabHrefParams['status'] = $tabKey;
            }
            $isActive = ($tabKey === 'all' && $status === 'all') || ($tabKey !== 'all' && $status === $tabKey);
            $countKey = $tabKey === 'all' ? 'all' : $tabKey;
        @endphp
        <a
            href="{{ route('admin.reviews.index', $tabHrefParams) }}"
            @class(['admin-review-tabs__link', 'admin-review-tabs__link--active' => $isActive])
        >{{ $tabLabel }} <span class="admin-review-tabs__count">({{ $statusCounts[$countKey] }})</span></a>
    @endforeach
</nav>

<form method="get" action="{{ route('admin.reviews.index') }}" class="admin-review-filter">
    @if($status !== 'all')
        <input type="hidden" name="status" value="{{ $status }}">
    @endif
    <label>
        Врач:
        <select name="doctor_id" class="form-control" style="max-width:22rem; margin-top:0.35rem;" onchange="this.form.submit()">
            <option value="">Все врачи</option>
            @foreach($doctors as $d)
                <option value="{{ $d->id }}" @selected((string) request('doctor_id') === (string) $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
    </label>
    <noscript><button type="submit" class="btn" style="margin-top:0.5rem;">Применить</button></noscript>
</form>

@if($reviews->isEmpty())
    <p class="admin-review-empty">В этом разделе пока нет отзывов.</p>
@else
<table class="admin admin-review-table">
    <thead>
        <tr>
            <th>Дата</th>
            <th>Врач</th>
            <th>Автор</th>
            <th>Фрагмент</th>
            <th>Оц.</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
    @foreach($reviews as $r)
        <tr @class(['admin-review-row--pending' => $r->status === Review::STATUS_PENDING])>
            <td>{{ $r->created_at->format('d.m.Y H:i') }}</td>
            <td>
                @if($r->doctor)
                    <a href="{{ route('admin.doctors.edit', $r->doctor) }}">{{ $r->doctor->name }}</a>
                @else
                    —
                @endif
            </td>
            <td>
                <a href="{{ route('admin.reviews.show', ['review' => $r] + $reviewQuery) }}">{{ $r->name }}</a>
            </td>
            <td class="admin-review-table__fragment">{{ \Illuminate\Support\Str::limit(strip_tags($r->body), 100) }}</td>
            <td>{{ $r->rating }}/5</td>
            <td>{{ Review::statusLabel($r->status) }}</td>
            <td style="white-space:nowrap;">
                @if($r->status === Review::STATUS_PENDING)
                    <form method="post" action="{{ route('admin.reviews.status', ['review' => $r] + $patchQuery) }}" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ Review::STATUS_APPROVED }}">
                        <button type="submit" class="btn btn--primary" style="font-size:0.75rem; padding:0.2rem 0.45rem;">Одобрить</button>
                    </form>
                    <form method="post" action="{{ route('admin.reviews.status', ['review' => $r] + $patchQuery) }}" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ Review::STATUS_REJECTED }}">
                        <button type="submit" class="btn" style="font-size:0.75rem; padding:0.2rem 0.45rem;">Отклонить</button>
                    </form>
                @endif
                <form method="post" action="{{ route('admin.reviews.destroy', ['review' => $r] + $reviewQuery) }}" onsubmit="return confirm('Удалить этот отзыв?');" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn" style="font-size:0.75rem; padding:0.2rem 0.45rem;">Удалить</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $reviews->links('pagination.minimal') }}
@endif
@endsection
