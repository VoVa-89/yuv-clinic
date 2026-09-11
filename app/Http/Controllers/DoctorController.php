<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(): View
    {
        $doctors = Doctor::query()->published()->orderBy('sort_order')->get();

        return view('public.doctors.index', compact('doctors'));
    }

    public function show(Doctor $doctor): View
    {
        if (! $doctor->is_published) {
            abort(404);
        }
        $doctor->load('services');

        return view('public.doctors.show', compact('doctor'));
    }
}
