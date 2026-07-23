<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function create(array $data, ?UploadedFile $file = null, ?UploadedFile $coverImage = null): Document
    {
        if ($file) {
            $data = array_merge($data, $this->storeFile($file));
        }

        if ($coverImage) {
            $data['cover_image'] = $coverImage->store('documents/covers', 'public');
        }

        $data['uploaded_by'] = auth()->id();

        $document = Document::create($data);
        FrontendCacheService::clearDocuments();

        return $document;
    }

    public function update(int $id, array $data, ?UploadedFile $file = null, ?UploadedFile $coverImage = null): Document
    {
        $document = Document::findOrFail($id);

        if ($file) {
            $oldFilePath = $document->file_path;
            $oldProvider = $document->storage_provider;
            $data = array_merge($data, $this->storeFile($file));
            if ($oldFilePath) {
                Storage::disk($oldProvider === 'google_drive' ? 'google' : 'local')->delete($oldFilePath);
            }
        }

        if ($coverImage) {
            $oldCoverImage = $document->cover_image;
            $data['cover_image'] = $coverImage->store('documents/covers', 'public');
            if ($oldCoverImage) {
                Storage::disk('public')->delete($oldCoverImage);
            }
        }

        $document->update($data);
        FrontendCacheService::clearDocuments();

        return $document->fresh();
    }

    public function delete(int $id): void
    {
        $document = Document::findOrFail($id);

        if ($document->file_path) {
            Storage::disk($document->storage_provider === 'google_drive' ? 'google' : 'local')->delete($document->file_path);
        }

        if ($document->cover_image) {
            Storage::disk('public')->delete($document->cover_image);
        }

        $document->delete();
        FrontendCacheService::clearDocuments();
    }

    public function toggleActive(int $id): void
    {
        $document = Document::findOrFail($id);
        $document->update(['is_active' => !$document->is_active]);
        FrontendCacheService::clearDocuments();
    }

    private function storeFile(UploadedFile $file): array
    {
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        $driveFileName = uniqid() . '_' . $originalName;
        Storage::disk('google')->put($driveFileName, fopen($file->getRealPath(), 'r'));

        return [
            'file_path' => $driveFileName,
            'storage_provider' => 'google_drive',
            'file_name' => $originalName,
            'file_type' => $mimeType,
            'file_size' => $size,
        ];
    }

    public function getStatistics(): array
    {
        $stats = Document::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month,
            SUM(download_count) as total_downloads
        ', [now()->month, now()->year])->first();

        $byCategory = Document::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        return [
            'total'            => (int) $stats->total,
            'active'           => (int) $stats->active,
            'this_month'       => (int) $stats->this_month,
            'total_downloads'  => (int) $stats->total_downloads,
            'by_category'      => $byCategory,
        ];
    }
}
