<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ReviewAdminController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', 'all');
        $doctorId = $request->query('doctor_id');

        $q = Review::query()->with('doctor')->orderByDesc('created_at');

        if ($status === Review::STATUS_PENDING) {
            $q->where('status', Review::STATUS_PENDING);
        } elseif ($status === Review::STATUS_APPROVED) {
            $q->where('status', Review::STATUS_APPROVED);
        } elseif ($status === Review::STATUS_REJECTED) {
            $q->where('status', Review::STATUS_REJECTED);
        }

        if ($request->filled('doctor_id')) {
            $q->where('doctor_id', (int) $doctorId);
        }

        $reviews = $q->paginate(30)->withQueryString();

        $statusCounts = [
            'all' => Review::query()->count(),
            Review::STATUS_PENDING => Review::query()->where('status', Review::STATUS_PENDING)->count(),
            Review::STATUS_APPROVED => Review::query()->where('status', Review::STATUS_APPROVED)->count(),
            Review::STATUS_REJECTED => Review::query()->where('status', Review::STATUS_REJECTED)->count(),
        ];

        $doctors = Doctor::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.reviews.index', compact('reviews', 'status', 'statusCounts', 'doctors'));
    }

    public function show(Review $review): View
    {
        $review->load('doctor');

        return view('admin.reviews.show', compact('review'));
    }

    public function updateStatus(Request $request, Review $review): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:'.implode(',', [
                Review::STATUS_PENDING, Review::STATUS_APPROVED, Review::STATUS_REJECTED,
            ])],
        ]);
        $review->update(['status' => $request->input('status')]);

        return back()->with('status', 'Статус обновлён.');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()
            ->route('admin.reviews.index', array_filter([
                'status' => $request->query('status'),
                'doctor_id' => $request->query('doctor_id'),
            ], static fn ($v) => $v !== null && $v !== ''))
            ->with('status', 'Отзыв удалён.');
    }
}
