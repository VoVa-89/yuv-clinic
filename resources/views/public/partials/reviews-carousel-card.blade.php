{{-- Карточка отзыва в горизонтальной ленте (страница «Отзывы», стиль как на главной) --}}
@props([
    'review',
    'showDoctorInCard' => false,
])

@php
    use Illuminate\Support\Str;

    $plainBody = strip_tags((string) $review->body);
    $excerptLimit = 220;
    $isLongReview = Str::length($plainBody) > $excerptLimit;
    $excerptText = $isLongReview ? Str::limit($plainBody, $excerptLimit, '…') : $plainBody;
@endphp

<article
    id="review-{{ $review->id }}"
    class="home-reviews__card reviews-page__review-card"
    itemscope
    itemtype="https://schema.org/Review"
>
    <div itemprop="itemReviewed" itemscope itemtype="https://schema.org/Dentist" class="visually-hidden">
        <meta itemprop="name" content="{{ config('clinic.name') }}">
    </div>
    <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating" class="sr-only">
        <meta itemprop="ratingValue" content="{{ $review->rating }}">
        <meta itemprop="bestRating" content="5">
    </span>
    <div class="home-reviews__card-name" itemprop="author" itemscope itemtype="https://schema.org/Person">
        <span itemprop="name">{{ $review->name }}</span>
    </div>
    @if($showDoctorInCard && $review->doctor)
        <p class="home-reviews__card-line">
            <span class="home-reviews__card-label">Врач:</span> {{ $review->doctor->name }}
        </p>
    @endif
    <p class="reviews-page__card-meta">
        <time itemprop="datePublished" datetime="{{ $review->created_at->toIso8601String() }}">{{ $review->created_at->translatedFormat('d.m.Y') }}</time>
    </p>
    <p class="home-reviews__card-rating" aria-label="Оценка {{ $review->rating }} из 5">
        @for($s = 1; $s <= 5; $s++)
            <span aria-hidden="true" @class(['home-reviews__star', 'home-reviews__star--on' => $s <= $review->rating])>★</span>
        @endfor
        <span class="home-reviews__rating-num">{{ $review->rating }}/5</span>
    </p>

    <div class="reviews-page__card-stack">
        @if($isLongReview)
            <div class="reviews-page__card-copy">
                <p class="home-reviews__card-text reviews-page__review-text" data-review-excerpt>{{ $excerptText }}</p>
                <div
                    id="review-full-{{ $review->id }}"
                    class="home-reviews__card-text reviews-page__review-text reviews-page__review-full"
                    data-review-full
                    hidden
                    itemprop="reviewBody"
                >{{ $review->body }}</div>
            </div>
            <div class="reviews-page__card-actions">
                <button
                    type="button"
                    class="home-reviews__card-more reviews-page__review-toggle"
                    data-review-toggle
                    data-label-expand="Читать ещё"
                    data-label-collapse="Свернуть"
                    aria-expanded="false"
                    aria-controls="review-full-{{ $review->id }}"
                >Читать ещё</button>
            </div>
        @else
            <div class="reviews-page__card-copy reviews-page__card-copy--short">
                <p class="home-reviews__card-text reviews-page__review-text" itemprop="reviewBody">{{ $review->body }}</p>
            </div>
        @endif
    </div>
</article>
