<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\Auth\LoginPage;
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
    Route::get('/', function () {
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
});
