@extends('layouts.admin')
@section('title', 'Новая услуга')
@section('content')
<h1 class="admin__h1">Новая услуга</h1>
<form method="post" action="{{ route('admin.services.store') }}">
    @csrf
    <p><label>Название *<br><input class="form-control" type="text" name="title" value="{{ old('title') }}" required></label></p>
    <p><label>URL-фрагмент (slug, опц.)<br><input class="form-control" type="text" name="slug" value="{{ old('slug') }}" placeholder="avtomaticheski-iz-nazvaniya"></label></p>
    <p><label>Кратко<br><input class="form-control" type="text" name="short_description" value="{{ old('short_description') }}"></label></p>
    <p><label>Описание (HTML)<br><textarea class="wys form-control" name="body" rows="8">{{ old('body') }}</textarea></label></p>
    <p><label>Цена/диапазон (текст)<br><input class="form-control" type="text" name="price_text" value="{{ old('price_text') }}"></label></p>
    <p><label>Порядок<br><input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', 0) }}"></label></p>
    <p><label><input type="checkbox" name="is_published" value="1" @checked(old('is_published', true))> Опубликовано</label></p>
    <p><label>Meta title<br><input class="form-control" type="text" name="meta_title" value="{{ old('meta_title') }}"></label></p>
    <p><label>Meta description<br><input class="form-control" type="text" name="meta_description" value="{{ old('meta_description') }}"></label></p>
    <p>Врачи:</p>
    @foreach($doctors as $d)
        <label style="display:block;"><input type="checkbox" name="doctor_ids[]" value="{{ $d->id }}" @checked(in_array($d->id, old('doctor_ids', []), true))> {{ $d->name }}</label>
    @endforeach
    <p><button class="btn btn--primary" type="submit">Сохранить</button> <a href="{{ route('admin.services.index') }}">Отмена</a></p>
</form>
@endsection
