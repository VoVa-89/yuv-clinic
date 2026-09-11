<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Review;
use App\Models\Service;
use App\Models\Doctor;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'pendingReviews' => Review::query()->where('status', Review::STATUS_PENDING)->count(),
            'servicesCount' => Service::query()->count(),
            'doctorsCount' => Doctor::query()->count(),
            'documentsCount' => Document::query()->count(),
        ]);
    }
}
