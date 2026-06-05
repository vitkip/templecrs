<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\Personnel\PersonnelForm;
use App\Livewire\Personnel\PersonnelShow;
use App\Livewire\Personnel\PersonnelTable;
use App\Livewire\Settings\SettingsPage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes — Buddhist EMS
|--------------------------------------------------------------------------
*/

// Apply locale middleware to all routes
Route::middleware([SetLocale::class])->group(function () {

    // Dashboard — redirects to personnel for now
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
});
