@extends('layouts.admin')
@section('title', 'Редактирование: '.$service->title)
@section('content')
<h1 class="admin__h1">Редактирование</h1>
<form method="post" action="{{ route('admin.services.update', $service) }}">
    @csrf @method('PUT')
    <p><label>Название *<br><input class="form-control" type="text" name="title" value="{{ old('title', $service->title) }}" required></label></p>
    <p><label>URL-фрагмент<br><input class="form-control" type="text" name="slug" value="{{ old('slug', $service->slug) }}"></label></p>
    <p><label>Кратко<br><input class="form-control" type="text" name="short_description" value="{{ old('short_description', $service->short_description) }}"></label></p>
    <p><label>Описание<br><textarea class="wys form-control" name="body" rows="8">{{ old('body', $service->body) }}</textarea></label></p>
    <p><label>Цена/диапазон<br><input class="form-control" type="text" name="price_text" value="{{ old('price_text', $service->price_text) }}"></label></p>
    <p><label>Порядок<br><input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order) }}"></label></p>
    <p><label><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $service->is_published))> Опубликовано</label></p>
    <p><label>Meta title<br><input class="form-control" type="text" name="meta_title" value="{{ old('meta_title', $service->meta_title) }}"></label></p>
    <p><label>Meta description<br><input class="form-control" type="text" name="meta_description" value="{{ old('meta_description', $service->meta_description) }}"></label></p>
    <p>Врачи:</p>
    @php $sel = old('doctor_ids', $service->doctors->pluck('id')->all()); @endphp
    @foreach($doctors as $d)
        <label style="display:block;"><input type="checkbox" name="doctor_ids[]" value="{{ $d->id }}" @checked(in_array($d->id, $sel, true))> {{ $d->name }}</label>
    @endforeach
    <p><button class="btn btn--primary" type="submit">Сохранить</button> <a href="{{ route('admin.services.index') }}">Назад</a></p>
</form>
@endsection
