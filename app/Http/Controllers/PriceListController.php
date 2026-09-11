<?php

namespace App\Http\Controllers;

use App\Models\PriceItem;
use Illuminate\View\View;

class PriceListController extends Controller
{
    public function __invoke(): View
    {
        $sectionTitles = PriceItem::sectionTitles();
        $keys = array_keys($sectionTitles);

        $itemsBySection = PriceItem::query()
            ->ordered()
            ->get()
            ->groupBy('section');

        return view('public.prices.show', [
            'sectionKeys' => $keys,
            'sectionTitles' => $sectionTitles,
            'itemsBySection' => $itemsBySection,
        ]);
    }
}
