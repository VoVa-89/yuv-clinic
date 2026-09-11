<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicReviewRequest;
use App\Models\Doctor;
use App\Models\Review;
use App\Services\RecaptchaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReviewPageController extends Controller
{
    public function __construct(
        private RecaptchaService $recaptcha
    ) {}

    public function index(): View
    {
        $doctorsWithReviews = Doctor::query()
            ->published()
            ->whereHas('reviews', static fn ($q) => $q->approved())
            ->with([
                'reviews' => static fn ($q) => $q
                    ->approved()
                    ->latest()
                    ->with('doctor:id,name,slug'),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        /** @var Collection<int, Review> $reviewsOther */
        $reviewsOther = Review::query()
            ->approved()
            ->where(static function ($q): void {
                $q->whereNull('doctor_id')
                    ->orWhereHas('doctor', static fn ($dq) => $dq->where('is_published', false));
            })
            ->latest()
            ->with('doctor:id,name,slug,is_published')
            ->get();

        $doctorsForReview = Doctor::query()
            ->published()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('public.reviews.index', [
            'doctorsWithReviews' => $doctorsWithReviews,
            'reviewsOther' => $reviewsOther,
            'avg' => Review::averageRatingApproved(),
            'count' => Review::countApproved(),
            'doctorsForReview' => $doctorsForReview,
        ]);
    }

    public function store(StorePublicReviewRequest $request): RedirectResponse
    {
        if (! $this->recaptcha->verify(
            $request->input('g-recaptcha-response'),
            $request->ip()
        )) {
            return back()->withInput()->withErrors(['captcha' => 'Проверка reCAPTCHA не пройдена.']);
        }

        Review::query()->create([
            'doctor_id' => (int) $request->validated('doctor_id'),
            'name' => strip_tags($request->input('name')),
            'body' => strip_tags($request->input('body')),
            'rating' => (int) $request->input('rating'),
            'status' => Review::STATUS_PENDING,
            'ip' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        return redirect()->route('reviews.index')->with('status', 'Спасибо! Отзыв отправлен на модерацию.');
    }
}
