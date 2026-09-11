@extends('layouts.admin')
@section('title', 'Загрузка документа')
@section('content')
<h1 class="admin__h1">Новый документ</h1>
<form method="post" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data">
    @csrf
    <p><label>Название *<br><input class="form-control" type="text" name="title" value="{{ old('title') }}" required></label></p>
    <p><label>Файл (pdf, jpg, png, webp, до 10 Мб) *<br><input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png,.webp"></label></p>
    <p><label>Порядок<br><input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', 0) }}"></label></p>
    <p><label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))> Активен</label></p>
    <p><button class="btn btn--primary" type="submit">Загрузить</button> <a href="{{ route('admin.documents.index') }}">Отмена</a></p>
</form>
@endsection
