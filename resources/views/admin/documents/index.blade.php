@extends('layouts.admin')
@section('title', 'Документы')
@section('content')
<p><a class="btn btn--primary" href="{{ route('admin.documents.create') }}">+ Загрузить</a></p>
<table class="admin">
    <thead><tr><th>Название</th><th>Файл</th><th>Активен</th><th></th></tr></thead>
    <tbody>
    @foreach($documents as $doc)
        <tr>
            <td><a href="{{ route('admin.documents.edit', $doc) }}">{{ $doc->title }}</a></td>
            <td><a href="{{ $doc->publicStorageHref() }}" target="_blank" rel="noopener">открыть</a></td>
            <td>{{ $doc->is_active ? 'да' : 'нет' }}</td>
            <td>
                <form method="post" action="{{ route('admin.documents.destroy', $doc) }}" onsubmit="return confirm('Удалить?');" style="display:inline">@csrf @method('DELETE')<button type="submit" class="btn" style="font-size:0.8rem;">Удалить</button></form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $documents->links('pagination.minimal') }}
@endsection
