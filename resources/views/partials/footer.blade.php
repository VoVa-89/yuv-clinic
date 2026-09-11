<footer class="footer site__footer" role="contentinfo">
    <div class="footer__inner">
        <div class="footer__grid">
            <div>
                <h2 class="footer__title">{{ config('clinic.name') }}</h2>
                @if(config('clinic.legal_name'))
                    <p>{{ config('clinic.legal_name') }}</p>
                @endif
                <p>{{ config('clinic.address') }}</p>
                @if(config('clinic.legal_address') && config('clinic.legal_address') !== config('clinic.address'))
                    <p>Юр. адрес: {{ config('clinic.legal_address') }}</p>
                @endif
                <p>Тел.: <a href="tel:{{ preg_replace('/\s+/', '', config('clinic.phone')) }}">{{ config('clinic.phone') }}</a>@if(config('clinic.phone_second')), <a href="tel:{{ preg_replace('/\s+/', '', config('clinic.phone_second')) }}">{{ config('clinic.phone_second') }}</a>@endif<br>
                <a href="mailto:{{ config('clinic.email') }}">{{ config('clinic.email') }}</a></p>
            </div>
            <div>
                <h2 class="footer__title">Реквизиты</h2>
                @if(config('clinic.ogrn'))
                    <p>{{ config('clinic.ogrn_label') }}: {{ config('clinic.ogrn') }}</p>
                @endif
                @if(config('clinic.inn'))
                    <p>ИНН: {{ config('clinic.inn') }}</p>
                @endif
                @if(config('clinic.license_number'))
                    <p>
                        Лицензия: {{ config('clinic.license_number') }}
                        @if(config('clinic.license_date'))
                            от {{ config('clinic.license_date') }}
                        @endif
                        @if(config('clinic.license_issuer'))
                            <br>выдана: {{ config('clinic.license_issuer') }}
                        @endif
                    </p>
                @endif
                <p>
                    <a href="{{ route('privacy') }}">Политика конфиденциальности</a><br>
                    <a href="{{ route('documents') }}">Лицензии и документы</a>
                </p>
            </div>
        </div>
        <div class="footer__disclaimers">
            <p>Имеются противопоказания. Необходима консультация специалиста. Информация на сайте не заменяет очную консультацию врача.</p>
            <p>Контент носит информационный характер и <strong>не является публичной офертой</strong> (ст.&nbsp;437 ГК РФ). Возрастная метка: {{ config('clinic.age_notice') }}.</p>
        </div>
    </div>
</footer>
