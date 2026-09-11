@extends('layouts.admin')
@section('title', 'Документ: '.$document->title)
@section('content')
<h1 class="admin__h1">Редактирование</h1>
<form method="post" action="{{ route('admin.documents.update', $document) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <p><label>Название *<br><input class="form-control" type="text" name="title" value="{{ old('title', $document->title) }}" required></label></p>
    <p>Текущий файл: <a href="{{ $document->publicStorageHref() }}" target="_blank" rel="noopener">скачать</a></p>
    <p><label>Новый файл (опц.)<br><input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png,.webp"></label></p>
    <p><label>Порядок<br><input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $document->sort_order) }}"></label></p>
    <p><label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $document->is_active))> Активен</label></p>
    <p><button class="btn btn--primary" type="submit">Сохранить</button> <a href="{{ route('admin.documents.index') }}">Назад</a></p>
</form>
@endsection
