<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        $doctors = Doctor::query()->published()->orderBy('sort_order')->get();

        return view('public.about', compact('doctors'));
    }
}
