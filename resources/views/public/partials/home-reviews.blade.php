{{-- Ожидается коллекция $homeReviews одобренных отзывов с подгруженным doctor --}}
@if(isset($homeReviews) && $homeReviews->isNotEmpty())
<section class="home-reviews" aria-labelledby="home-reviews-title" data-home-reviews>
    <div class="home-reviews__inner">
        <div class="home-reviews__top">
            <div class="home-reviews__head">
                <h2 id="home-reviews-title" class="home-reviews__title">Отзывы наших пациентов</h2>
                <a class="home-reviews__cta" href="{{ route('reviews.index') }}">
                    <span>Все отзывы</span>
                    <span class="home-reviews__cta-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </span>
                </a>
            </div>
            <div class="home-reviews__controls">
                <button type="button" class="home-reviews__nav-btn" data-home-reviews-prev aria-label="Предыдущие отзывы">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button type="button" class="home-reviews__nav-btn" data-home-reviews-next aria-label="Следующие отзывы">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
        <div class="home-reviews__viewport" data-home-reviews-track tabindex="0" role="region" aria-roledescription="carousel" aria-label="Лента отзывов">
            <div class="home-reviews__track">
                @foreach($homeReviews as $rev)
                    <article class="home-reviews__card">
                        <div class="home-reviews__card-name">{{ $rev->name }}</div>
                        @if($rev->doctor)
                            <p class="home-reviews__card-line"><span class="home-reviews__card-label">Врач:</span> {{ $rev->doctor->name }}</p>
                        @endif
                        <p class="home-reviews__card-rating" aria-label="Оценка {{ $rev->rating }} из 5">
                            @for($s = 1; $s <= 5; $s++)
                                <span aria-hidden="true" @class(['home-reviews__star', 'home-reviews__star--on' => $s <= $rev->rating])>★</span>
                            @endfor
                            <span class="home-reviews__rating-num">{{ $rev->rating }}/5</span>
                        </p>
                        <div class="home-reviews__card-stack">
                            <div class="home-reviews__card-copy">
                                <p class="home-reviews__card-text">{{ \Illuminate\Support\Str::limit(strip_tags($rev->body), 160) }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
