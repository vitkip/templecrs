<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('documents:migrate-to-drive {--dry-run} {--chunk=20}')]
#[Description('Upload existing locally-stored documents to Google Drive and update their records')]
class MigrateDocumentsToDrive extends Command
{
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $chunk = (int) $this->option('chunk');

        $query = Document::withTrashed()
            ->whereNotNull('file_path')
            ->where('storage_provider', 'local');

        $total = $query->count();
        if ($total === 0) {
            $this->info('No local documents to migrate.');
            return self::SUCCESS;
        }

        $this->info("Migrating {$total} document(s) to Google Drive" . ($dryRun ? ' (dry run)' : '') . '...');

        $migrated = 0;
        $failed = 0;

        $query->chunkById($chunk, function ($documents) use (&$migrated, &$failed, $dryRun) {
            foreach ($documents as $document) {
                if (!Storage::disk('local')->exists($document->file_path)) {
                    $this->warn("  [skip] #{$document->id} {$document->file_name} — local file missing at {$document->file_path}");
                    $failed++;
                    continue;
                }

                $driveFileName = uniqid() . '_' . $document->file_name;

                if ($dryRun) {
                    $this->line("  [dry-run] #{$document->id} {$document->file_name} -> google:{$driveFileName}");
                    $migrated++;
                    continue;
                }

                try {
                    Storage::disk('google')->put(
                        $driveFileName,
                        Storage::disk('local')->readStream($document->file_path)
                    );

                    $document->update([
                        'file_path' => $driveFileName,
                        'storage_provider' => 'google_drive',
                    ]);

                    $this->line("  [ok] #{$document->id} {$document->file_name}");
                    $migrated++;
                } catch (\Throwable $e) {
                    $this->error("  [fail] #{$document->id} {$document->file_name} — {$e->getMessage()}");
                    $failed++;
                }
            }
        });

        $this->newLine();
        $this->info("Done. Migrated: {$migrated}, Failed/Skipped: {$failed}.");
        if (!$dryRun && $migrated > 0) {
            $this->comment('Local files were left in place as a safety net — clean them up manually once Drive copies are verified.');
        }

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
