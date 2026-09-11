@extends('layouts.admin')
@section('title', 'Врач: '.$doctor->name)
@section('content')
<h1 class="admin__h1">Редактирование</h1>
@include('admin.partials.validation-errors')
<form method="post" action="{{ route('admin.doctors.update', $doctor) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <p><label>ФИО *<br><input class="form-control" type="text" name="name" value="{{ old('name', $doctor->name) }}" required></label></p>
    <p><label>Slug<br><input class="form-control" type="text" name="slug" value="{{ old('slug', $doctor->slug) }}"></label></p>
    <p><label>Должность *<br><input class="form-control" type="text" name="position" value="{{ old('position', $doctor->position) }}" required></label></p>
    @if($doctor->photo)
        <p>Текущее фото:<br>
            <img src="{{ \Illuminate\Support\Str::startsWith($doctor->photo, ['http://', 'https://']) ? $doctor->photo : asset(ltrim($doctor->photo, '/')) }}" alt="" width="120" height="120" style="width:auto; max-height:140px; border-radius:8px; object-fit:cover;">
        </p>
    @endif
    <p><label>Новое фото (заменит текущее)<br><input class="form-control" type="file" name="photo_file" accept="image/jpeg,image/png,image/webp"></label></p>
    <p><label>Путь / URL к фото (если не загружаете файл)<br><input class="form-control" type="text" name="photo" value="{{ old('photo', $doctor->photo) }}"></label></p>
    <p><label>Биография<br><textarea class="wys form-control" name="bio" rows="10">{{ old('bio', $doctor->bio) }}</textarea></label></p>
    <p><label>Порядок<br><input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $doctor->sort_order) }}"></label></p>
    <p><label><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $doctor->is_published))> Опубликовано</label></p>
    <p><label>Meta title<br><input class="form-control" type="text" name="meta_title" value="{{ old('meta_title', $doctor->meta_title) }}"></label></p>
    <p><label>Meta description<br><input class="form-control" type="text" name="meta_description" value="{{ old('meta_description', $doctor->meta_description) }}"></label></p>
    @php $sel = old('service_ids', $doctor->services->pluck('id')->all()); @endphp
    <p>Услуги:</p>
    @foreach($services as $s)
        <label style="display:block;"><input type="checkbox" name="service_ids[]" value="{{ $s->id }}" @checked(in_array($s->id, $sel, true))> {{ $s->title }}</label>
    @endforeach
    <p><button class="btn btn--primary" type="submit">Сохранить</button> <a href="{{ route('admin.doctors.index') }}">Назад</a></p>
</form>
@endsection
