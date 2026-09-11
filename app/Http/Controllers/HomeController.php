<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Review;
use App\Models\Service;
use Illuminate\View\View;

final class HomeController extends Controller
{
    public function __invoke(): View
    {
        $services = Service::query()->published()->orderBy('sort_order')->limit(6)->get();
        $doctors = Doctor::query()->published()->orderBy('sort_order')->get();

        $homeReviews = Review::query()
            ->approved()
            ->with(['doctor:id,name,slug'])
            ->latest()
            ->limit(12)
            ->get();

        return view('public.home', compact('services', 'doctors', 'homeReviews'));
    }
}
