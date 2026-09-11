{{-- Карточка направления на главной: variant = featured | elevated | visual — вся карточка одна ссылка --}}
@php
    $showUrl = route('services.show', $service);
    $iconSet = (int) ($iconSet ?? 0);
    $showPrice = (bool) ($showPrice ?? false);
    /** @var string|null $homeTeaser путь к картинке из config clinic.home_service_teasers */
    $homeTeaser = config('clinic.home_service_teasers.'.$service->slug);
@endphp

@if($homeTeaser)
    <a href="{{ $showUrl }}" class="service-card service-card--visual service-card--visual-photo">
        <div class="service-card__visual-bg service-card__visual-bg--photo" aria-hidden="true">
            <img
                class="service-card__visual-img"
                src="{{ asset($homeTeaser) }}"
                alt=""
                width="640"
                height="640"
                loading="lazy"
                decoding="async"
            >
        </div>
        <div class="service-card__visual-overlay">
            <h3 class="service-card__heading service-card__heading--on-photo">{{ $service->title }}</h3>
            @if($service->short_description)
                <p class="service-card__excerpt service-card__excerpt--on-photo">{{ \Illuminate\Support\Str::limit($service->short_description, 140) }}</p>
            @endif
            @if($showPrice && $service->price_text)
                <p class="service-card__price service-card__price--on-photo">{{ $service->price_text }}</p>
            @endif
        </div>
    </a>
@elseif(($variant ?? 'elevated') === 'featured')
    <a href="{{ $showUrl }}" class="service-card service-card--featured">
        <div class="service-card__featured-icon" aria-hidden="true">
            @if($iconSet === 0)
                <svg viewBox="0 0 24 24" width="72" height="72" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path fill="rgba(255,255,255,0.92)" d="M12 2C9.3 2 7.5 5.2 7 10c-.6 5.5 0 10 2.3 12C10.4 24 12 24 12 22c0 2 1.6 2 2.7 0C17 20 17.6 15.5 17 10c-.5-4.8-2.3-8-5-8z"/></svg>
            @elseif($iconSet === 1)
                <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" width="72" height="72" aria-hidden="true"><circle cx="24" cy="38" r="7" stroke="rgba(255,255,255,0.92)" stroke-width="4" fill="none"/><circle cx="40" cy="38" r="7" stroke="rgba(255,255,255,0.92)" stroke-width="4" fill="none"/><path stroke="rgba(255,255,255,0.92)" stroke-width="3" stroke-linecap="round" d="M20 52c8 14 26 13 34-10"/></svg>
            @else
                <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" width="72" height="72" aria-hidden="true"><path stroke="rgba(255,255,255,0.92)" stroke-width="4" stroke-linecap="round" d="M12 52V20l20 14 20-14v32"/><path stroke="rgba(255,255,255,0.92)" stroke-width="3.5" d="M20 52h26"/></svg>
            @endif
        </div>
        <h3 class="service-card__heading">{{ $service->title }}</h3>
        @if($service->short_description)
            <p class="service-card__excerpt">{{ $service->short_description }}</p>
        @endif
        @if($showPrice && $service->price_text)
            <p class="service-card__price service-card__price--featured">{{ $service->price_text }}</p>
        @endif
        <span class="service-card__chev" aria-hidden="true">→</span>
    </a>
@elseif(($variant ?? 'elevated') === 'elevated')
    <a href="{{ $showUrl }}" class="service-card service-card--elevated">
        <h3 class="service-card__heading service-card__heading--standard">{{ $service->title }}</h3>
        @if($service->short_description)
            <p class="service-card__excerpt service-card__excerpt--muted">{{ $service->short_description }}</p>
        @endif
        @if($showPrice && $service->price_text)
            <p class="service-card__price service-card__price--elevated">{{ $service->price_text }}</p>
        @endif
        <span class="service-card__more">Подробнее</span>
    </a>
@else
    <a href="{{ $showUrl }}" class="service-card service-card--visual service-card--visual-{{ $iconSet }}">
        <div class="service-card__visual-bg" aria-hidden="true"></div>
        <div class="service-card__visual-overlay">
            <h3 class="service-card__heading service-card__heading--on-photo">{{ $service->title }}</h3>
            @if($service->short_description)
                <p class="service-card__excerpt service-card__excerpt--on-photo">{{ \Illuminate\Support\Str::limit($service->short_description, 140) }}</p>
            @endif
            @if($showPrice && $service->price_text)
                <p class="service-card__price service-card__price--on-photo">{{ $service->price_text }}</p>
            @endif
        </div>
    </a>
@endif
