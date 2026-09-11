<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use App\Support\HtmlSanitizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClinicSeeder extends Seeder
{
    public function run(): void
    {
        $username = config('clinic.admin.username', 'admin');
        $email = config('clinic.admin.email', 'admin@yuv-clinic.local');
        // Ищем по email (как в старых сидах), обновляем логин и пароль
        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'username' => $username,
                'name' => config('clinic.admin.name', 'Администратор'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'UVclinic')),
                'is_admin' => true,
            ]
        );

        $yuraBio = <<<'HTML'
<p>Стоматолог общей практики, стаж работы более 12 лет.</p>
<ul>
<li>Диплом ГОУ СПО ЛО «Медицинский колледж в г. Тихвине» (29.06.2011);</li>
<li>Сертификат ГОУ СПО ЛО «Медицинский колледж в г. Тихвине» по специальности «Стоматология» (02.07.2011);</li>
<li>Сертификат по специальности «Стоматология» ГОУ ВПО «СПбГМУ им. акад. И. П. Павлова (06.06.2015);</li>
<li>Сертификат «Препарирование корневых каналов (K3, TF-файлами), systems Elements Obturation / System B», СПб (15.11.2014);</li>
<li>Сертификат «Сложный стоматологический пациент», СПб (10.12.2014);</li>
<li>Диплом специалиста ФГБОУ ВО «Чувашский государственный университет им. И. Н. Ульянова», г. Чебоксары, «Стоматология» (04.07.2019);</li>
<li>Аккредитация «Стоматология общей практики» (10.07.2019);</li>
<li>Удостоверение о повышении квалификации ООО ДПО «Академия имени Стрельникова», г. Рязань, «Стоматология общей практики» (08.04.2024).</li>
</ul>
HTML;

        $vasyaBio = <<<'HTML'
<p>Врач-стоматолог-ортопед, стаж работы более 18 лет.</p>
<ul>
<li>В 2008 г. окончил «СевГУ» (г. Архангельск), врач-стоматолог, диплом ВСГ № 2475502.</li>
<li>Проф. переподготовка (2009), Санкт-Петербургская медицинская академия посл. образования, стоматология ортопедическая.</li>
<li>Проф. переподготовка (2013), СЗГМУ им. И. И. Мечникова, «Организация здравоохранения и общественное здоровье».</li>
</ul>
HTML;

        $yura = Doctor::query()->updateOrCreate(
            ['slug' => 'yuriy-krupenkin'],
            [
                'name' => 'Крупенькин Юрий Олегович',
                'position' => 'Стоматолог общей практики',
                'photo' => '/images/doctors/1.webp',
                'bio' => HtmlSanitizer::clean($yuraBio),
                'sort_order' => 1,
                'is_published' => true,
                'meta_title' => 'Крупенькин Ю. О. — врач '.config('clinic.name_short').', '.config('clinic.city'),
            ]
        );

        $vasya = Doctor::query()->updateOrCreate(
            ['slug' => 'vasiliy-hlybov'],
            [
                'name' => 'Хлыбов Василий Сергеевич',
                'position' => 'Врач-стоматолог-ортопед',
                'photo' => '/images/doctors/2.webp',
                'bio' => HtmlSanitizer::clean($vasyaBio),
                'sort_order' => 2,
                'is_published' => true,
                'meta_title' => 'Хлыбов В. С. — врач '.config('clinic.name_short').', '.config('clinic.city'),
            ]
        );

        $s0 = Service::query()->updateOrCreate(
            ['slug' => 'konsultatsiya-stomatologa'],
            [
                'title' => 'Консультация стоматолога',
                'short_description' => 'Осмотр, сбор анамнеза, план обследования и рекомендации.',
                'body' => HtmlSanitizer::clean('<p>Первичный или повторный приём для уточнения жалоб и дальнейшего плана лечения по показаниям.</p>'),
                'price_text' => 'уточняется при записи',
                'sort_order' => 0,
                'is_published' => true,
            ]
        );

        $s1 = Service::query()->updateOrCreate(
            ['slug' => 'terapiya'],
            [
                'title' => 'Терапевтическая стоматология',
                'short_description' => 'Лечение кариеса, эндодонтия, восстановление зубов.',
                'body' => HtmlSanitizer::clean('<p>Диагностика и лечение заболеваний зубов с использованием современных материалов. План лечения согласуем после осмотра.</p>'),
                'price_text' => 'от 2 000 ₽',
                'sort_order' => 1,
                'is_published' => true,
            ]
        );
        $s2 = Service::query()->updateOrCreate(
            ['slug' => 'hirurgiya'],
            [
                'title' => 'Хирургическая стоматология',
                'short_description' => 'Удаления, подготовка к протезированию и имплантации.',
                'body' => HtmlSanitizer::clean('<p>Амбулаторные вмешательства по показаниям, после информированного согласия.</p>'),
                'price_text' => 'по прейскуранту',
                'sort_order' => 2,
                'is_published' => true,
            ]
        );
        $s3 = Service::query()->updateOrCreate(
            ['slug' => 'ortopediya'],
            [
                'title' => 'Ортопедия',
                'short_description' => 'Съёмные и несъёмные конструкции, коронки, мосты.',
                'body' => HtmlSanitizer::clean('<p>Подбор вариантов ортопедического лечения в зависимости от клинической ситуации.</p>'),
                'price_text' => 'уточняется при плане',
                'sort_order' => 3,
                'is_published' => true,
            ]
        );
        $s4 = Service::query()->updateOrCreate(
            ['slug' => 'gigiena'],
            [
                'title' => 'Профессиональная гигиена',
                'short_description' => 'Удаление зубных отложений, полировка, рекомендации по уходу.',
                'body' => HtmlSanitizer::clean('<p>Процедуры для поддержания здоровья дёсен и тканей зуба.</p>'),
                'price_text' => 'от 3 500 ₽',
                'sort_order' => 4,
                'is_published' => true,
            ]
        );
        $s5 = Service::query()->updateOrCreate(
            ['slug' => 'estetika'],
            [
                'title' => 'Эстетическая стоматология',
                'short_description' => 'Отбеливание, реставрации фронтальной группы зубов.',
                'body' => HtmlSanitizer::clean('<p>Набор мероприятий согласуем после осмотра и оценки ожидаемого результата.</p>'),
                'price_text' => 'по запросу',
                'sort_order' => 5,
                'is_published' => true,
            ]
        );

        $yura->services()->sync([$s0->id, $s1->id, $s2->id, $s4->id, $s5->id]);
        $vasya->services()->sync([$s0->id, $s2->id, $s3->id, $s5->id]);

        if (! Review::query()->where('name', 'Пациент (пример)')->exists()) {
            Review::query()->create([
                'doctor_id' => $yura->id,
                'name' => 'Пациент (пример)',
                'body' => 'Пример одобренного отзыва. Клиника чистая, врач всё подробно объяснил. Рекомендую — после согласования плана и цены.',
                'rating' => 5,
                'status' => Review::STATUS_APPROVED,
            ]);
        }
        if (! Review::query()->where('name', 'Проверка модерации')->exists()) {
            Review::query()->create([
                'doctor_id' => $vasya->id,
                'name' => 'Проверка модерации',
                'body' => 'Этот отзыв в статусе ожидания — проверьте в админке.',
                'rating' => 4,
                'status' => Review::STATUS_PENDING,
            ]);
        }
    }
}
