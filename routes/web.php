<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentPageController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\ReviewPageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PriceListController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceAdminController;
use App\Http\Controllers\Admin\DoctorAdminController;
use App\Http\Controllers\Admin\ReviewAdminController;
use App\Http\Controllers\Admin\DocumentAdminController;
use App\Http\Controllers\Admin\PriceItemAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/o-klinike', AboutController::class)->name('about');
Route::get('/uslugi-i-tseny', [ServiceController::class, 'index'])->name('services.index');
Route::get('/tseny-na-uslugi', PriceListController::class)->name('prices.index');
Route::get('/uslugi-i-tseny/{service:slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/vrachi', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/vrachi/{doctor:slug}', [DoctorController::class, 'show'])->name('doctors.show');
Route::get('/otzyvy', [ReviewPageController::class, 'index'])->name('reviews.index');
Route::post('/otzyvy', [ReviewPageController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('reviews.store');
Route::get('/dokumenty', DocumentPageController::class)->name('documents');
Route::get('/kontakty', ContactController::class)->name('contacts');
Route::get('/politika-konfidentsialnosti', PrivacyController::class)->name('privacy');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', function () {
    $sitemap = rtrim((string) config('app.url'), '/').'/sitemap.xml';
    $body = "User-agent: *\nDisallow: /admin\nDisallow: /login\n\nSitemap: {$sitemap}\n";

    return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('robots');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('services', ServiceAdminController::class);
    Route::resource('price-items', PriceItemAdminController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('doctors', DoctorAdminController::class);
    Route::resource('reviews', ReviewAdminController::class)->only(['index', 'show', 'destroy']);
    Route::patch('reviews/{review}/status', [ReviewAdminController::class, 'updateStatus'])->name('reviews.status');
    Route::resource('documents', DocumentAdminController::class);
});
