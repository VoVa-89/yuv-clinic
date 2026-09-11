<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PriceItemAdminController extends Controller
{
    public function index(): View
    {
        $sectionTitles = PriceItem::sectionTitles();
        $keys = array_keys($sectionTitles);

        $grouped = PriceItem::query()
            ->ordered()
            ->get()
            ->groupBy('section');

        return view('admin.price_items.index', [
            'sectionKeys' => $keys,
            'sectionTitles' => $sectionTitles,
            'grouped' => $grouped,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'section' => ['required', 'string', Rule::in(PriceItem::allowedSectionKeys())],
            'procedure_name' => ['required', 'string', 'max:500'],
            'nomenclature_code' => ['nullable', 'string', 'max:64'],
            'price_display' => ['required', 'string', 'max:128'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.price-items.index')
                ->withErrors($validator)
                ->withInput()
                ->with('price_item_add_section', $request->input('section'));
        }

        $data = $validator->validated();
        if (($data['nomenclature_code'] ?? '') === '') {
            $data['nomenclature_code'] = null;
        }
        $data['sort_order'] = (int) (PriceItem::query()
            ->where('section', $data['section'])
            ->max('sort_order') ?? 0) + 1;

        PriceItem::query()->create($data);

        return redirect()->route('admin.price-items.index')->with('status', 'Позиция добавлена.');
    }

    public function update(Request $request, PriceItem $priceItem): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'procedure_name' => ['required', 'string', 'max:500'],
            'nomenclature_code' => ['nullable', 'string', 'max:64'],
            'price_display' => ['required', 'string', 'max:128'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.price-items.index')
                ->withErrors($validator)
                ->withInput()
                ->with('price_item_edit_id', $priceItem->id);
        }

        $data = $validator->validated();
        if (($data['nomenclature_code'] ?? '') === '') {
            $data['nomenclature_code'] = null;
        }

        $priceItem->update($data);

        return redirect()->route('admin.price-items.index')->with('status', 'Сохранено.');
    }

    public function destroy(PriceItem $priceItem): RedirectResponse
    {
        $priceItem->delete();

        return redirect()->route('admin.price-items.index')->with('status', 'Удалено.');
    }
}
