<?php

use App\Http\Controllers\FinanceReportController;
use App\Http\Controllers\FrontendController;
use App\Http\Middleware\SetLocale;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Dashboard;
use App\Livewire\Documents\CategoryManager as DocumentCategoryManager;
use App\Livewire\Documents\DocumentForm;
use App\Livewire\Documents\DocumentShow;
use App\Livewire\Documents\DocumentTable;
use App\Livewire\News\CategoryManager as NewsCategoryManager;
use App\Livewire\News\NewsForm;
use App\Livewire\News\NewsShow;
use App\Livewire\News\NewsTable;
use App\Livewire\Finance\CategoryManager;
use App\Livewire\Finance\FinancePage;
use App\Livewire\Finance\FinanceReport;
use App\Livewire\Finance\TransactionForm;
use App\Livewire\Finance\TransactionTable;
use App\Livewire\HeroSlides\HeroSlideForm;
use App\Livewire\HeroSlides\HeroSlideTable;
use App\Livewire\Personnel\PersonnelForm;
use App\Livewire\Personnel\PersonnelShow;
use App\Livewire\Personnel\PersonnelTable;
use App\Livewire\Settings\SettingsPage;
use App\Livewire\Profile\ProfilePage;
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
    Route::get('/articles', [FrontendController::class, 'newsIndex'])->name('frontend.news');
    Route::get('/article/{id}', [FrontendController::class, 'show'])->name('frontend.news.show');
    Route::get('/committee', [FrontendController::class, 'personnelIndex'])->name('frontend.personnel');
    Route::get('/committee/{id}', [FrontendController::class, 'personnelShow'])->name('frontend.personnel.show');
    Route::get('/library', [FrontendController::class, 'documentsIndex'])->name('frontend.documents');
    Route::get('/about', [FrontendController::class, 'about'])->name('frontend.about');
    Route::get('/library/{id}/download', function (int $id) {
        $document = \App\Models\Document::where('is_active', true)->findOrFail($id);
        abort_if(!$document->file_path, 404);
        abort_unless(\Illuminate\Support\Facades\Storage::disk('local')->exists($document->file_path), 404);
        $document->increment('download_count');
        return \Illuminate\Support\Facades\Storage::disk('local')->download(
            $document->file_path,
            $document->file_name
        );
    })->name('frontend.document.download');
});

// ─── Language Switcher (public — works for both frontend and admin) ───
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['lo', 'en'])) {
        Session::put('locale', $locale);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale.switch');

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
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // ─── Personnel Routes ───
    Route::prefix('personnel')->name('personnel.')->group(function () {
        Route::get('/', PersonnelTable::class)->name('index');
        Route::get('/create', PersonnelForm::class)->name('create');
        Route::get('/{id}', PersonnelShow::class)->name('show');
        Route::get('/{id}/edit', PersonnelForm::class)->name('edit');
    });

    // ─── Settings ───
    Route::get('/settings', SettingsPage::class)->name('settings');

    // ─── Profile ───
    Route::get('/profile', ProfilePage::class)->name('profile');

    // ─── User Management ───
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', UserTable::class)->name('index');
        Route::get('/create', UserForm::class)->name('create');
        Route::get('/{id}/edit', UserForm::class)->name('edit');
    });

    // ─── Document Management (DMS) ───
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', DocumentTable::class)->name('index');
        Route::get('/categories', DocumentCategoryManager::class)->name('categories.index');
        Route::get('/create', DocumentForm::class)->name('create');
        Route::get('/{id}/download', function (int $id) {
            $document = \App\Models\Document::findOrFail($id);
            abort_if(!$document->file_path, 404);
            abort_unless(\Illuminate\Support\Facades\Storage::disk('local')->exists($document->file_path), 404);
            $document->increment('download_count');
            return \Illuminate\Support\Facades\Storage::disk('local')->download(
                $document->file_path,
                $document->file_name
            );
        })->name('download');
        Route::get('/{id}', DocumentShow::class)->name('show');
        Route::get('/{id}/edit', DocumentForm::class)->name('edit');
    });

    // ─── News Management ───
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/', NewsTable::class)->name('index');
        Route::get('/categories', NewsCategoryManager::class)->name('categories.index');
        Route::get('/create', NewsForm::class)->name('create');
        Route::get('/{id}', NewsShow::class)->name('show');
        Route::get('/{id}/edit', NewsForm::class)->name('edit');
    });

    // ─── Finance Management ───
    Route::get('/finance', FinancePage::class)->name('finance.index');
    Route::get('/finance/report', FinanceReport::class)->name('finance.report');
    Route::get('/finance/report/pdf/finance-report.pdf', [FinanceReportController::class, 'pdf'])->name('finance.report.pdf');

    Route::prefix('finance/transactions')->name('finance.transactions.')->group(function () {
        Route::get('/',           TransactionTable::class)->name('index');
        Route::get('/create',     TransactionForm::class)->name('create');
        Route::get('/{id}/edit',  TransactionForm::class)->name('edit');
    });
    Route::get('/finance/categories', CategoryManager::class)->name('finance.categories.index');

    // ─── Hero Slides Management ───
    Route::prefix('hero-slides')->name('hero-slides.')->group(function () {
        Route::get('/', HeroSlideTable::class)->name('index');
        Route::get('/create', HeroSlideForm::class)->name('create');
        Route::get('/{id}/edit', HeroSlideForm::class)->name('edit');
    });
});

// ─── Diagnostic Route for File Upload issues (super_admin only) ───
Route::middleware(['auth'])->get('/diagnose-upload', function () {
    abort_unless(auth()->user()->isSuperAdmin(), 403);
    $results = [];

    // 1. PHP Version & fileinfo
    $results['php_version'] = PHP_VERSION;
    $results['fileinfo_extension_loaded'] = extension_loaded('fileinfo') ? 'Yes' : 'No';

    // 2. PHP Upload configurations
    $results['upload_max_filesize'] = ini_get('upload_max_filesize');
    $results['post_max_size'] = ini_get('post_max_size');
    $results['memory_limit'] = ini_get('memory_limit');

    // 3. Storage and livewire-tmp paths writability
    $storageApp = storage_path('app');
    $results['storage_app_writable'] = is_writable($storageApp) ? 'Yes' : 'No';

    $livewireTmp = storage_path('app/livewire-tmp');
    if (!file_exists($livewireTmp)) {
        $created = @mkdir($livewireTmp, 0755, true);
        $results['livewire_tmp_exists'] = 'No (Tried creating: ' . ($created ? 'Success' : 'Failed') . ')';
    } else {
        $results['livewire_tmp_exists'] = 'Yes';
    }
    
    $results['livewire_tmp_writable'] = is_writable($livewireTmp) ? 'Yes' : 'No';

    // 4. Test writing to livewire-tmp
    $testFile = $livewireTmp . '/test_write.txt';
    $writeTest = @file_put_contents($testFile, 'test');
    if ($writeTest !== false) {
        $results['file_write_test'] = 'Success';
        @unlink($testFile);
    } else {
        $results['file_write_test'] = 'Failed (' . (error_get_last()['message'] ?? 'Unknown error') . ')';
    }

    // 5. Test writing to public disk
    try {
        \Illuminate\Support\Facades\Storage::disk('public')->put('test.txt', 'test');
        $results['public_disk_write'] = 'Success';
        \Illuminate\Support\Facades\Storage::disk('public')->delete('test.txt');
    } catch (\Exception $e) {
        $results['public_disk_write'] = 'Failed (' . $e->getMessage() . ')';
    }

    // 6. Get last few parsed errors from laravel.log
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $content = file_get_contents($logFile);
        // Match log lines starting with timestamp [YYYY-MM-DD HH:MM:SS]
        preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/', $content, $matches, PREG_OFFSET_CAPTURE);
        if (!empty($matches[0])) {
            $lastThreeEntries = array_slice($matches[0], -3);
            $parsedLogs = [];
            foreach ($lastThreeEntries as $idx => $match) {
                $startPos = $match[1];
                $endPos = isset($lastThreeEntries[$idx + 1]) ? $lastThreeEntries[$idx + 1][1] : strlen($content);
                $entryText = substr($content, $startPos, $endPos - $startPos);
                $lines = explode("\n", $entryText);
                // Get the timestamp line and the next 8 lines (usually includes message & main trace frame)
                $parsedLogs[] = array_map('trim', array_slice($lines, 0, 8));
            }
            $results['last_errors_parsed'] = $parsedLogs;
        } else {
            $results['last_errors_parsed'] = 'No timestamped logs found';
        }
    } else {
        $results['last_errors_parsed'] = 'No log file found';
    }


    return response()->json($results, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

