@extends('layouts.admin')
@section('title', 'Услуги')
@section('content')
<p><a class="btn btn--primary" href="{{ route('admin.services.create') }}">+ Добавить</a></p>
<table class="admin">
    <thead><tr><th>Название</th><th>Цена (текст)</th><th>Опубл.</th><th></th></tr></thead>
    <tbody>
    @foreach($services as $s)
        <tr>
            <td><a href="{{ route('admin.services.edit', $s) }}">{{ $s->title }}</a></td>
            <td>{{ $s->price_text }}</td>
            <td>{{ $s->is_published ? 'да' : 'нет' }}</td>
            <td>
                <form method="post" action="{{ route('admin.services.destroy', $s) }}" onsubmit="return confirm('Удалить?');" style="display:inline">@csrf @method('DELETE')<button type="submit" class="btn" style="font-size:0.8rem;">Удалить</button></form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $services->links('pagination.minimal') }}
@endsection
