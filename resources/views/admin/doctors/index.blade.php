@extends('layouts.admin')
@section('title', 'Врачи')
@section('content')
<p><a class="btn btn--primary" href="{{ route('admin.doctors.create') }}">+ Добавить</a></p>
<table class="admin">
    <thead><tr><th>Имя</th><th>Должность</th><th>Опубл.</th><th></th></tr></thead>
    <tbody>
    @foreach($doctors as $d)
        <tr>
            <td><a href="{{ route('admin.doctors.edit', $d) }}">{{ $d->name }}</a></td>
            <td>{{ $d->position }}</td>
            <td>{{ $d->is_published ? 'да' : 'нет' }}</td>
            <td>
                <form method="post" action="{{ route('admin.doctors.destroy', $d) }}" onsubmit="return confirm('Удалить?');" style="display:inline">@csrf @method('DELETE')<button type="submit" class="btn" style="font-size:0.8rem;">Удалить</button></form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $doctors->links('pagination.minimal') }}
@endsection
