@extends('layouts.app')
@section('title', ($service->meta_title ?: $service->title).' — '.config('clinic.name'))
@section('meta_description', $service->meta_description ?: $service->short_description)
@section('content')
<article>
    <div class="page-head">
        <h1 class="page-head__h1">{{ $service->title }}</h1>
        @if($service->short_description)
            <p class="page-head__lead">{{ $service->short_description }}</p>
        @endif
    </div>
    <div style="max-width:45rem; margin:0 auto;">
        @if($service->body)
            <div class="prose">{!! $service->body !!}</div>
        @endif
        @if($priceSectionKey === null && $service->price_text)
            <p><strong>Цена:</strong> {{ $service->price_text }}</p>
        @endif
        <p class="not-offer">Информация не является публичной офертой.</p>
        @if($service->doctors->isNotEmpty())
            <h2 style="font-size:1.1rem; margin-top:1.5rem;">Врачи</h2>
            <ul>
                @foreach($service->doctors as $d)
                    <li><a href="{{ route('doctors.show', $d) }}">{{ $d->name }}</a></li>
                @endforeach
            </ul>
        @endif
    </div>

    @if($priceSectionKey !== null)
        <section class="service-page-prices" aria-labelledby="service-pricelist-h" style="max-width:52rem; margin:2rem auto 0; padding:0 0.25rem;">
            <h2 id="service-pricelist-h" class="page-head__h1" style="font-size:1.15rem; margin-bottom:0.75rem;">Прейскурант: {{ $priceSectionTitle }}</h2>
            <p class="price-page__intro" style="margin-bottom:1rem;">Ориентиры по разделу. Полный перечень — на странице <a href="{{ route('prices.index') }}">«Цены на наши услуги»</a>.</p>
            <div class="price-table-wrap">
                <table class="price-table">
                    <thead>
                        <tr>
                            <th scope="col">Процедура</th>
                            <th scope="col">Стоимость</th>
                        </tr>
                    </thead>
                    @include('public.partials.price-table-section-tbody', [
                        'sectionTitle' => $priceSectionTitle,
                        'rows' => $priceItems,
                    ])
                </table>
            </div>
        </section>
    @endif

    <p style="text-align:center; margin-top:1.5rem;"><a href="{{ route('services.index') }}">← Все услуги</a></p>
</article>
@endsection
