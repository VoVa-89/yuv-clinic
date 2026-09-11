@extends('layouts.app')
@section('title', 'Отзывы — '.config('clinic.name').', '.config('clinic.city'))
@section('meta_description', 'Отзывы пациентов о '.config('clinic.name').' в '.config('clinic.city').'.')
@section('head_extra')
@if($count > 0 && $avg)
@php
    $reviewLd = [
        '@context' => 'https://schema.org',
        '@type' => 'MedicalOrganization',
        'name' => config('clinic.name'),
        'aggregateRating' => [
            '@type' => 'AggregateRating',
            'ratingValue' => (string) $avg,
            'reviewCount' => (string) $count,
            'bestRating' => '5',
            'worstRating' => '1',
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($reviewLd, JSON_UNESCAPED_UNICODE) !!}</script>
@endif
@endsection
@section('content')
<div class="reviews-page" data-reviews-page>
    <header class="page-head">
        <h1 class="page-head__h1">Отзывы</h1>
        <p class="page-head__lead">Мнения пациентов проходят модерацию перед публикацией (ФЗ&nbsp;№&nbsp;152-ФЗ, внутренние правила клиники).</p>
    </header>

    @if(session('status'))
        <div class="alert alert--success reviews-page__flash">{{ session('status') }}</div>
    @endif

@if($doctorsForReview->isNotEmpty())
    <nav class="reviews-page__jump" aria-label="Разделы страницы">
        <a class="reviews-page__jump-link" href="#review-form">Оставить отзыв</a>
    </nav>
@endif

    <section id="reviews-list" class="reviews-page__list-section" aria-labelledby="reviews-list-title">
        <h2 id="reviews-list-title" class="visually-hidden">Опубликованные отзывы</h2>

        @if($count > 0 && $avg !== null)
            <div class="reviews-page__aggregate" aria-label="Сводка по опубликованным отзывам">
                <div class="reviews-page__aggregate-score" aria-hidden="true">
                    @php $avgRounded = round((float) $avg, 1); @endphp
                    <span class="reviews-page__aggregate-num">{{ number_format($avgRounded, 1, ',', '') }}</span>
                    <span class="reviews-page__aggregate-stars">
                        @for($s = 1; $s <= 5; $s++)
                            <span class="reviews-page__aggregate-star {{ $s <= round((float) $avg) ? 'reviews-page__aggregate-star--on' : '' }}">★</span>
                        @endfor
                    </span>
                </div>
                @php
                    $c = (int) $count;
                    $c100 = $c % 100;
                    $c10 = $c % 10;
                    $reviewsWord = ($c100 >= 11 && $c100 <= 14)
                        ? 'отзывов'
                        : (match (true) {
                            $c10 === 1 => 'отзыв',
                            $c10 >= 2 && $c10 <= 4 => 'отзыва',
                            default => 'отзывов',
                        });
                @endphp
                <p class="reviews-page__aggregate-text">
                    Средняя оценка <strong>{{ number_format($avgRounded, 1, ',', '') }}</strong> из 5 на основе <strong>{{ number_format($count, 0, ',', ' ') }}</strong> {{ $reviewsWord }}.
                </p>
            </div>
        @else
            <p class="reviews-page__empty">Пока нет опубликованных отзывов — вы можете стать первым, кто поделится впечатлением.</p>
        @endif

        @php
            $hasReviewBlocks = $doctorsWithReviews->isNotEmpty() || $reviewsOther->isNotEmpty();
        @endphp
        @if($hasReviewBlocks)
            @foreach($doctorsWithReviews as $doctor)
                <section
                    id="reviews-doctor-{{ $doctor->slug }}"
                    class="reviews-page__doctor-section"
                    aria-labelledby="reviews-doctor-{{ $doctor->slug }}-title"
                >
                    @include('public.partials.reviews-doctor-carousel', [
                        'reviews' => $doctor->reviews,
                        'headingId' => 'reviews-doctor-' . $doctor->slug . '-title',
                        'heading' => $doctor->name,
                        'carouselLabel' => 'Отзывы о ' . $doctor->name,
                        'ctaHref' => route('doctors.show', $doctor),
                        'ctaText' => 'Страница врача',
                        'showDoctorInCard' => false,
                    ])
                </section>
            @endforeach

            @if($reviewsOther->isNotEmpty())
                <section
                    id="reviews-other"
                    class="reviews-page__doctor-section"
                    aria-labelledby="reviews-other-title"
                >
                    @include('public.partials.reviews-doctor-carousel', [
                        'reviews' => $reviewsOther,
                        'headingId' => 'reviews-other-title',
                        'heading' => 'Прочие отзывы',
                        'carouselLabel' => 'Прочие опубликованные отзывы',
                        'ctaHref' => null,
                        'ctaText' => null,
                        'showDoctorInCard' => true,
                    ])
                </section>
            @endif
        @endif
    </section>

    <section id="review-form" class="reviews-page__form-section" aria-labelledby="review-form-title">
        <h2 id="review-form-title" class="reviews-page__form-heading">Оставить отзыв</h2>
        <p class="reviews-page__form-note">После отправки отзыв появится на сайте только после проверки модератором.</p>

        @if($errors->any())
            <div class="alert alert--error reviews-page__form-errors" role="alert">
                <p class="reviews-page__form-errors-title">Проверьте поля формы:</p>
                <ul class="reviews-page__form-errors-list">
                    @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($doctorsForReview->isEmpty())
            <p class="not-offer">Сейчас нельзя выбрать врача для отзыва. Загляните позже или свяжитесь с клиникой.</p>
        @else
            <form class="review-form reviews-page__form" method="post" action="{{ route('reviews.store') }}">
                @csrf
                {{-- Honeypot: заполняют боты; люди поле не видят --}}
                <div class="review-form__hp" aria-hidden="true">
                    <label for="rev-hp-website">Не заполнять</label>
                    <input id="rev-hp-website" type="text" name="website" value="" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                </div>
                <div class="review-form__row">
                    <label for="rev-name">Имя</label>
                    <input id="rev-name" type="text" name="name" value="{{ old('name') }}" required maxlength="100" autocomplete="name">
                    <p class="review-form__hint">Как к вам обращаться на сайте (до 100 символов).</p>
                    @error('name')<div class="review-form__error">{{ $message }}</div>@enderror
                </div>
                <div class="review-form__row">
                    <label for="rev-doctor">О каком враче ваш отзыв?</label>
                    <select id="rev-doctor" name="doctor_id" required>
                        <option value="" disabled @selected(old('doctor_id', '') === '')>Выберите врача</option>
                        @foreach($doctorsForReview as $doc)
                            <option value="{{ $doc->id }}" @selected((string) old('doctor_id') === (string) $doc->id)>{{ $doc->name }}</option>
                        @endforeach
                    </select>
                    <p class="review-form__hint">Отзыв привязывается к карточке врача на сайте.</p>
                    @error('doctor_id')<div class="review-form__error">{{ $message }}</div>@enderror
                </div>
                <div class="review-form__row review-form__row--rating">
                    <label id="rev-rating-label" for="rev-rating">Оценка</label>
                    <p id="rev-rating-hint" class="review-form__hint">От 1 до 5 звёзд; можно выбрать и в списке ниже.</p>
                    <div class="review-form__stars" role="radiogroup" aria-labelledby="rev-rating-label">
                        @foreach(range(5, 1) as $starVal)
                            <button type="button" class="review-form__star-btn" data-star-value="{{ $starVal }}" aria-label="{{ $starVal }} из 5" aria-pressed="{{ (int) old('rating', 5) === $starVal ? 'true' : 'false' }}">
                                <span class="review-form__star-glyph" aria-hidden="true">★</span>
                            </button>
                        @endforeach
                    </div>
                    <select id="rev-rating" name="rating" class="visually-hidden" required aria-describedby="rev-rating-hint">
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected((int) old('rating', 5) === $i)>{{ $i }} из 5</option>
                        @endfor
                    </select>
                    @error('rating')<div class="review-form__error">{{ $message }}</div>@enderror
                </div>
                <div class="review-form__row">
                    <label for="rev-body">Текст отзыва</label>
                    <textarea id="rev-body" name="body" required minlength="10" maxlength="5000">{{ old('body') }}</textarea>
                    <p class="review-form__hint review-form__counter" data-char-count>
                        <span data-char-count-current>0</span> / 5000 символов. Не менее 10 символов.
                    </p>
                    @error('body')<div class="review-form__error">{{ $message }}</div>@enderror
                </div>
                <div class="review-form__row review-form__agree">
                    <input type="checkbox" name="agree" id="agree" value="1" @checked(old('agree')) required>
                    <label for="agree">Согласен(на) на <a href="{{ route('privacy') }}" target="_blank" rel="noopener">обработку персональных данных</a></label>
                </div>
                @error('agree')<div class="review-form__error">{{ $message }}</div>@enderror

                @if(config('clinic.recaptcha.site_key'))
                    <div class="review-form__row">
                        <div class="g-recaptcha" data-sitekey="{{ config('clinic.recaptcha.site_key') }}"></div>
                        @error('captcha')<div class="review-form__error">{{ $message }}</div>@enderror
                    </div>
                @endif

                <button type="submit" class="btn btn--primary">Отправить</button>
            </form>
        @endif
    </section>
</div>
@push('scripts')
@if(config('clinic.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
@endpush
@endsection
