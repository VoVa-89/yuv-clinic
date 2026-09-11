{{-- Лента отзывов о враче (карусель как на главной) --}}
@props([
    'reviews',
    'headingId',
    'heading',
    'carouselLabel',
    'ctaHref' => null,
    'ctaText' => null,
    'showDoctorInCard' => false,
])

<div data-home-reviews>
    <div class="home-reviews__inner">
        <div class="home-reviews__top">
            <div class="home-reviews__head">
                <h3 id="{{ $headingId }}" class="home-reviews__title">{{ $heading }}</h3>
                @if($ctaHref && $ctaText)
                    <a class="home-reviews__cta" href="{{ $ctaHref }}">
                        <span>{{ $ctaText }}</span>
                        <span class="home-reviews__cta-icon" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    </a>
                @endif
            </div>
            @if($reviews->count() > 1)
                <div class="home-reviews__controls">
                    <button type="button" class="home-reviews__nav-btn" data-home-reviews-prev aria-label="Предыдущие отзывы">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button type="button" class="home-reviews__nav-btn" data-home-reviews-next aria-label="Следующие отзывы">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            @endif
        </div>
        <div
            class="home-reviews__viewport"
            data-home-reviews-track
            tabindex="0"
            role="region"
            aria-roledescription="carousel"
            aria-label="{{ $carouselLabel }}"
        >
            <div class="home-reviews__track">
                @foreach($reviews as $review)
                    @include('public.partials.reviews-carousel-card', ['review' => $review, 'showDoctorInCard' => $showDoctorInCard])
                @endforeach
            </div>
        </div>
    </div>
</div>
