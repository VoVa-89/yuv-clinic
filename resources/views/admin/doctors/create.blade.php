@extends('layouts.admin')
@section('title', 'Новый врач')
@section('content')
<h1 class="admin__h1">Новый врач</h1>
@include('admin.partials.validation-errors')
<form method="post" action="{{ route('admin.doctors.store') }}" enctype="multipart/form-data">
    @csrf
    <p><label>ФИО *<br><input class="form-control" type="text" name="name" value="{{ old('name') }}" required></label></p>
    <p><label>Slug (опц.)<br><input class="form-control" type="text" name="slug" value="{{ old('slug') }}"></label></p>
    <p><label>Должность *<br><input class="form-control" type="text" name="position" value="{{ old('position') }}" required></label></p>
    <p><label>Фото (JPEG, PNG, WebP, до 4 МБ)<br><input class="form-control" type="file" name="photo_file" accept="image/jpeg,image/png,image/webp"></label></p>
    <p><label>Или путь / URL к фото (если файл не загружаете)<br><input class="form-control" type="text" name="photo" value="{{ old('photo') }}" placeholder="/images/doctors/1.webp"></label></p>
    <p><label>Биография (HTML)<br><textarea class="wys form-control" name="bio" rows="10">{{ old('bio') }}</textarea></label></p>
    <p><label>Порядок<br><input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', 0) }}"></label></p>
    <p><label><input type="checkbox" name="is_published" value="1" @checked(old('is_published', true))> Опубликовано</label></p>
    <p><label>Meta title<br><input class="form-control" type="text" name="meta_title" value="{{ old('meta_title') }}"></label></p>
    <p><label>Meta description<br><input class="form-control" type="text" name="meta_description" value="{{ old('meta_description') }}"></label></p>
    <p>Услуги:</p>
    @foreach($services as $s)
        <label style="display:block;"><input type="checkbox" name="service_ids[]" value="{{ $s->id }}" @checked(in_array($s->id, old('service_ids', []), true))> {{ $s->title }}</label>
    @endforeach
    <p><button class="btn btn--primary" type="submit">Сохранить</button> <a href="{{ route('admin.doctors.index') }}">Отмена</a></p>
</form>
@endsection
