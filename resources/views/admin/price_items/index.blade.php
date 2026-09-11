@extends('layouts.admin')
@section('title', 'Прайс-лист')
@section('content')
<h1>Прайс-лист</h1>

@if ($errors->any())
    <div class="alert alert--error">
        <ul style="margin:0; padding-left:1.2rem;">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<p>Разделы фиксированы. Добавляйте и редактируйте строки в каждом блоке. Поле «Код по номенклатуре» (Приказ Минздрава №&nbsp;804н) рекомендуется заполнять для каждой позиции прайса.</p>

@foreach($sectionKeys as $sectionKey)
    @php
        $label = $sectionTitles[$sectionKey];
        $items = ($grouped[$sectionKey] ?? collect());
    @endphp
    <section style="margin-bottom:2rem;">
        <h2 class="page-head__h1" style="font-size:1.1rem; margin-bottom:0.5rem;">{{ $label }}</h2>
        <table class="admin">
            <thead>
                <tr>
                    <th>Процедуры и стоимость</th>
                </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <form id="price-item-update-{{ $item->id }}" method="post" action="{{ route('admin.price-items.update', $item) }}" class="sr-only" aria-hidden="true" tabindex="-1">
                            @csrf
                            @method('PATCH')
                        </form>
                        <div class="admin-price-item">
                            <label class="admin-price-item__procedure">Процедура (наименование по номенклатуре)
                                <input class="form-control" type="text" name="procedure_name" form="price-item-update-{{ $item->id }}" value="{{ session('price_item_edit_id') == $item->id ? old('procedure_name', $item->procedure_name) : $item->procedure_name }}" required maxlength="500">
                            </label>
                            <label class="admin-price-item__code">Код 804н
                                <input class="form-control" type="text" name="nomenclature_code" form="price-item-update-{{ $item->id }}" value="{{ session('price_item_edit_id') == $item->id ? old('nomenclature_code', $item->nomenclature_code) : $item->nomenclature_code }}" maxlength="64" placeholder="например A01.07.001" autocomplete="off">
                            </label>
                            <label class="admin-price-item__price">Стоимость
                                <input class="form-control" type="text" name="price_display" form="price-item-update-{{ $item->id }}" value="{{ session('price_item_edit_id') == $item->id ? old('price_display', $item->price_display) : $item->price_display }}" required maxlength="128" placeholder="1 000 рублей">
                            </label>
                            <button type="submit" class="btn btn--primary admin-price-item__save" form="price-item-update-{{ $item->id }}">Сохранить</button>
                            <form class="admin-price-item__delete" method="post" action="{{ route('admin.price-items.destroy', $item) }}" onsubmit="return confirm('Удалить строку?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn">Удалить</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <form class="admin-price-add" method="post" action="{{ route('admin.price-items.store') }}" style="margin-top:0.75rem; padding:0.75rem; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0;">
            @csrf
            <input type="hidden" name="section" value="{{ $sectionKey }}">
            <p style="margin:0 0 0.5rem; font-size:0.85rem; font-weight:600;">Добавить позицию</p>
            <label class="admin-price-add__procedure">Название процедуры (по номенклатуре)
                <input class="form-control" type="text" name="procedure_name" value="{{ (session('price_item_add_section', old('section')) === $sectionKey) ? old('procedure_name', '') : '' }}" required maxlength="500" placeholder="Официальное наименование услуги">
            </label>
            <label class="admin-price-add__code">Код по номенклатуре (804н)
                <input class="form-control" type="text" name="nomenclature_code" value="{{ (session('price_item_add_section', old('section')) === $sectionKey) ? old('nomenclature_code', '') : '' }}" maxlength="64" placeholder="например A01.07.001" autocomplete="off">
            </label>
            <div class="admin-price-add__row">
                <label class="admin-price-add__price">Стоимость
                    <input class="form-control" type="text" name="price_display" value="{{ (session('price_item_add_section', old('section')) === $sectionKey) ? old('price_display', '') : '' }}" required maxlength="128" placeholder="1 000 рублей">
                </label>
                <button type="submit" class="btn btn--primary admin-price-add__submit">Добавить</button>
            </div>
        </form>
    </section>
@endforeach
@endsection
