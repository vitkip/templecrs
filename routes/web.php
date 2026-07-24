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
    Route::get('/about/duties', [FrontendController::class, 'duties'])->name('frontend.duties');
    Route::get('/about/guide', [FrontendController::class, 'guide'])->name('frontend.guide');
    Route::get('/about/history', [FrontendController::class, 'history'])->name('frontend.history');
    Route::get('/library/{id}/download', function (int $id) {
        $document = \App\Models\Document::where('is_active', true)->findOrFail($id);
        abort_if(!$document->file_path, 404);
        $disk = $document->storage_provider === 'google_drive' ? 'google' : 'local';
        abort_unless(\Illuminate\Support\Facades\Storage::disk($disk)->exists($document->file_path), 404);
        $document->increment('download_count');
        return \Illuminate\Support\Facades\Storage::disk($disk)->download(
            $document->file_path,
            $document->file_name
        );
    })->name('frontend.document.download');

    // Inline file viewer (PDF in browser) — no increment, view only
    Route::get('/library/{id}/view', function (\Illuminate\Http\Request $request, int $id) {
        $document = \App\Models\Document::where('is_active', true)->findOrFail($id);
        abort_if(!$document->file_path, 404);
        $disk = $document->storage_provider === 'google_drive' ? 'google' : 'local';
        $storage = \Illuminate\Support\Facades\Storage::disk($disk);
        abort_unless($storage->exists($document->file_path), 404);

        // ETag/Last-Modified let the browser skip re-fetching from Google Drive
        // entirely on repeat views (304 Not Modified) — no network round-trip to Drive.
        $lastModified = $document->updated_at ?? now();
        $etag = sprintf('"%s-%s"', $document->id, $lastModified->timestamp);

        if ($request->headers->get('If-None-Match') === $etag) {
            return response('', 304, [
                'ETag' => $etag,
                'Cache-Control' => 'private, max-age=3600',
            ]);
        }

        $headers = [
            'Content-Type' => $document->file_type ?: 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($document->file_name) . '"',
            'Cache-Control' => 'private, max-age=3600',
            'ETag' => $etag,
            'Last-Modified' => $lastModified->toRfc7231String(),
            // Tell nginx/reverse proxies not to buffer the whole file before
            // relaying it — keeps this a true progressive stream end-to-end.
            'X-Accel-Buffering' => 'no',
        ];
        if ($document->file_size) {
            $headers['Content-Length'] = $document->file_size;
        }

        return response()->stream(function () use ($storage, $document) {
            $stream = $storage->readStream($document->file_path);
            abort_if($stream === false, 502, 'Unable to read file from storage.');

            // Disable output buffering so bytes reach the browser as they
            // arrive from the storage driver, instead of only after EOF.
            while (ob_get_level() > 0) {
                ob_end_flush();
            }

            while (!feof($stream)) {
                echo fread($stream, 8192);
                flush();
            }
            fclose($stream);
        }, 200, $headers);
    })->name('frontend.document.view');
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
            $disk = $document->storage_provider === 'google_drive' ? 'google' : 'local';
            abort_unless(\Illuminate\Support\Facades\Storage::disk($disk)->exists($document->file_path), 404);
            $document->increment('download_count');
            return \Illuminate\Support\Facades\Storage::disk($disk)->download(
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


    // 7. Google Drive document storage diagnostics
    $googleConfig = config('filesystems.disks.google');
    $credentialsPath = $googleConfig['credentials_path'] ?? null;
    $results['google_drive'] = [
        'credentials_path'        => $credentialsPath,
        'credentials_file_exists' => $credentialsPath && file_exists($credentialsPath) ? 'Yes' : 'No',
        'credentials_file_readable' => $credentialsPath && is_readable($credentialsPath) ? 'Yes' : 'No',
        'team_drive_id_set'       => !empty($googleConfig['team_drive_id']) ? 'Yes' : 'No',
        'shared_folder_id_set'    => !empty($googleConfig['shared_folder_id']) ? 'Yes' : 'No',
        'folder'                  => $googleConfig['folder'] ?? null,
        'curl_extension_loaded'   => extension_loaded('curl') ? 'Yes' : 'No',
    ];

    try {
        $testFileName = 'diagnose-test-' . uniqid() . '.txt';
        \Illuminate\Support\Facades\Storage::disk('google')->put($testFileName, 'diagnostic test');
        $exists = \Illuminate\Support\Facades\Storage::disk('google')->exists($testFileName);
        $results['google_drive']['live_write_test'] = 'Success';
        $results['google_drive']['live_exists_test'] = $exists ? 'Success' : 'Failed (file not found after write)';
        if ($exists) {
            \Illuminate\Support\Facades\Storage::disk('google')->delete($testFileName);
        }
    } catch (\Throwable $e) {
        $results['google_drive']['live_write_test'] = 'Failed: ' . get_class($e) . ' — ' . $e->getMessage();
    }

    // 8. Document storage_provider breakdown + per-file existence check
    $docsByProvider = \App\Models\Document::whereNotNull('file_path')
        ->select('id', 'file_name', 'file_path', 'storage_provider')
        ->get()
        ->map(function ($doc) {
            $disk = $doc->storage_provider === 'google_drive' ? 'google' : 'local';
            try {
                $exists = \Illuminate\Support\Facades\Storage::disk($disk)->exists($doc->file_path);
            } catch (\Throwable $e) {
                $exists = 'error: ' . $e->getMessage();
            }
            return [
                'id' => $doc->id,
                'file_name' => $doc->file_name,
                'storage_provider' => $doc->storage_provider,
                'exists_on_disk' => $exists,
            ];
        });
    $results['documents'] = $docsByProvider;

    return response()->json($results, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

