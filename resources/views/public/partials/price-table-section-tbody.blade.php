{{-- Один раздел таблицы прайса: заголовок раздела + строки. --}}
<tbody>
    <tr class="price-table__section">
        <th colspan="2" scope="colgroup">{{ $sectionTitle }}</th>
    </tr>
    @forelse($rows as $item)
        <tr class="price-table__row">
            <td>
                {{ $item->procedure_name }}
                @if($item->nomenclature_code)
                    <span class="price-table__code">{{ $item->nomenclature_code }}</span>
                @endif
            </td>
            <td>{{ $item->price_display }}</td>
        </tr>
    @empty
        <tr class="price-table__row">
            <td class="price-table__empty" colspan="2">Раздел заполняется.</td>
        </tr>
    @endforelse
</tbody>
