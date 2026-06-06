<?php

use App\Http\Controllers\FrontendController;
use App\Http\Middleware\SetLocale;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Documents\DocumentForm;
use App\Livewire\Documents\DocumentShow;
use App\Livewire\Documents\DocumentTable;
use App\Livewire\News\NewsForm;
use App\Livewire\News\NewsShow;
use App\Livewire\News\NewsTable;
use App\Livewire\HeroSlides\HeroSlideForm;
use App\Livewire\HeroSlides\HeroSlideTable;
use App\Livewire\Personnel\PersonnelForm;
use App\Livewire\Personnel\PersonnelShow;
use App\Livewire\Personnel\PersonnelTable;
use App\Livewire\Settings\SettingsPage;
use App\Livewire\Users\UserForm;
use App\Livewire\Users\UserTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes — Buddhist EMS
|--------------------------------------------------------------------------
*/

// ─── Public Frontend ───
Route::middleware([SetLocale::class])->group(function () {
    Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');
});

// ─── Auth Routes (no auth required) ───
Route::middleware(['guest'])->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
});

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

// ─── Protected Application Routes ───
Route::middleware(['auth', SetLocale::class])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return redirect()->route('personnel.index');
    })->name('dashboard');

    // ─── Language Switcher ───
    Route::get('/locale/{locale}', function (string $locale) {
        if (in_array($locale, ['lo', 'en'])) {
            Session::put('locale', $locale);
            app()->setLocale($locale);
        }
        return redirect()->back();
    })->name('locale.switch');

    // ─── Personnel Routes ───
    Route::prefix('personnel')->name('personnel.')->group(function () {
        Route::get('/', PersonnelTable::class)->name('index');
        Route::get('/create', PersonnelForm::class)->name('create');
        Route::get('/{id}', PersonnelShow::class)->name('show');
        Route::get('/{id}/edit', PersonnelForm::class)->name('edit');
    });

    // ─── Settings ───
    Route::get('/settings', SettingsPage::class)->name('settings');

    // ─── User Management ───
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', UserTable::class)->name('index');
        Route::get('/create', UserForm::class)->name('create');
        Route::get('/{id}/edit', UserForm::class)->name('edit');
    });

    // ─── Document Management (DMS) ───
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', DocumentTable::class)->name('index');
        Route::get('/create', DocumentForm::class)->name('create');
        Route::get('/{id}', DocumentShow::class)->name('show');
        Route::get('/{id}/edit', DocumentForm::class)->name('edit');
    });

    // ─── News Management ───
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', NewsTable::class)->name('index');
        Route::get('/create', NewsForm::class)->name('create');
        Route::get('/{id}', NewsShow::class)->name('show');
        Route::get('/{id}/edit', NewsForm::class)->name('edit');
    });

    // ─── Hero Slides Management ───
    Route::prefix('hero-slides')->name('hero-slides.')->group(function () {
        Route::get('/', HeroSlideTable::class)->name('index');
        Route::get('/create', HeroSlideForm::class)->name('create');
        Route::get('/{id}/edit', HeroSlideForm::class)->name('edit');
    });
});
