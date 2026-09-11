<?php

namespace App\Http\Controllers;

use App\Models\PriceItem;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()->published()->orderBy('sort_order')->get();

        return view('public.services.index', compact('services'));
    }

    public function show(Service $service): View
    {
        if (! $service->is_published) {
            abort(404);
        }
        $service->load('doctors');

        $priceSectionKey = config('service_price_section')[$service->slug] ?? null;
        $sectionTitles = config('price_sections', []);
        $priceSectionTitle = ($priceSectionKey && isset($sectionTitles[$priceSectionKey]))
            ? $sectionTitles[$priceSectionKey]
            : null;
        $priceItems = $priceSectionKey !== null
            ? PriceItem::query()->where('section', $priceSectionKey)->ordered()->get()
            : collect();

        return view('public.services.show', compact(
            'service',
            'priceSectionKey',
            'priceSectionTitle',
            'priceItems',
        ));
    }
}
