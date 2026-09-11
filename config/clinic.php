<?php

return [

    'name' => env('CLINIC_NAME', 'Стоматология Ю.В. Дент'),

    /** Короткое имя как в логотипе (шапка) */
    'name_short' => env('CLINIC_NAME_SHORT', 'Ю.В. Дент'),

    /** Верхняя строка бренда в шапке */
    'brand_kicker' => env('CLINIC_BRAND_KICKER', 'стоматология'),

    'slogan' => 'Ю.В.: Не просто буквы. Имена, которым доверяют',

    /** Подзаголовок в hero под слоганом */
    'hero_value' => env('CLINIC_HERO_VALUE', 'Современное оборудование, опытные врачи, честные цены.'),

    'city' => env('CLINIC_CITY', 'Петрозаводск'),

    /** Адрес оказания услуг (для пациентов) */
    'address' => env('CLINIC_ADDRESS', 'ул. Суворова, д. 1, г. Петрозаводск'),

    /**
     * Полное наименование оператора / медицинской организации (ООО/ИП …).
     * Для ЗоЗПП и 152-ФЗ. Пустое — блок в футере не дублирует name.
     */
    'legal_name' => env('CLINIC_LEGAL_NAME', ''),

    /** Юридический адрес (если отличается от адреса клиники) */
    'legal_address' => env('CLINIC_LEGAL_ADDRESS', ''),

    /** URL виджета Яндекс.Карт (Карты → Поделиться → HTML) */
    'map_embed_url' => env('CLINIC_MAP_EMBED_URL', 'https://yandex.ru/map-widget/v1/?ll=34.329909%2C61.784755&mode=search&oid=65936565107&ol=biz&sll=34.329909%2C61.784755&sspn=0.015631%2C0.004939&text=%D1%8E%20%D0%B2%20%D0%B4%D0%B5%D0%BD%D1%82&utm_content=add_review&utm_medium=reviews&utm_source=maps-reviews-widget&z=16.48'),

    /** Ссылка «открыть в Яндекс.Картах» (дублирует кликабельные подписи над виджетом) */
    'map_external_url' => env('CLINIC_MAP_EXTERNAL_URL', 'https://yandex.ru/maps/org/yu_v_dent/65936565107/?utm_medium=mapframe&utm_source=maps'),

    /** Фото входа на странице «Контакты» (путь от public/) */
    'entrance_photo' => env('CLINIC_ENTRANCE_PHOTO', 'images/contacts/entrance.webp'),

    'phone' => env('CLINIC_PHONE', '+7 (8142) 63-93-06'),

    'phone_second' => env('CLINIC_PHONE_SECOND', '+7 (911) 400-93-06'),

    'email' => env('CLINIC_EMAIL', 'yuvdent@yandex.ru'),

    /**
     * Режим работы (строки для блока на странице «Контакты»).
     *
     * @var list<string>
     */
    'opening_hours' => [
        'Пн — Пт: 10:00 — 20:00',
        'Сб: 10:00 — 15:00',
        'Вс: Выходной',
    ],

    'age_notice' => env('CLINIC_AGE_NOTICE', '0+'),

    'ogrn' => env('CLINIC_OGRN', ''),

    /** Подпись к номеру: ОГРН (юрлицо) или ОГРНИП (ИП) */
    'ogrn_label' => env('CLINIC_OGRN_LABEL', 'ОГРН'),

    'inn' => env('CLINIC_INN', ''),

    'license_number' => env('CLINIC_LICENSE_NUMBER', ''),

    /** Кем выдана лицензия */
    'license_issuer' => env('CLINIC_LICENSE_ISSUER', ''),

    /** Дата выдачи лицензии (строка для вывода, напр. 15.03.2020) */
    'license_date' => env('CLINIC_LICENSE_DATE', ''),

    /**
     * Сведения об уведомлении Роскомнадзора (152-ФЗ).
     * Пример: «Уведомление направлено …, регистрационный номер …»
     */
    'rkn_notification' => env('CLINIC_RKN_NOTIFICATION', ''),

    'gtm_id' => env('GTM_CONTAINER_ID', ''),

    /**
     * Превью на главной (путь от public/) по slug услуги.
     * Используйте только собственные или лицензированные изображения.
     *
     * @var array<string, string>
     */
    'home_service_teasers' => [
        'konsultatsiya-stomatologa' => 'images/services/konsultatsiya-stomatologa.png',
        'terapiya' => 'images/services/terapiya.png',
        'hirurgiya' => 'images/services/hirurgiya.png',
        'ortopediya' => 'images/services/ortopediya.png',
        'gigiena' => 'images/services/gigiena.png',
        'estetika' => 'images/services/estetika.png',
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY', ''),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    ],

    'admin' => [
        'username' => env('ADMIN_USERNAME', 'admin'),
        'email' => env('ADMIN_EMAIL', 'admin@yuv-clinic.local'),
        'name' => env('ADMIN_NAME', 'Администратор'),
    ],

];
