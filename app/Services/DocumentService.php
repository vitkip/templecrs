<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function create(array $data, ?UploadedFile $file = null): Document
    {
        if ($file) {
            $data = array_merge($data, $this->storeFile($file));
        }

        $data['uploaded_by'] = auth()->id();

        return Document::create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $file = null): Document
    {
        $document = Document::findOrFail($id);

        if ($file) {
            // Remove old file
            if ($document->file_path) {
                Storage::disk('local')->delete($document->file_path);
            }
            $data = array_merge($data, $this->storeFile($file));
        }

        $document->update($data);

        return $document->fresh();
    }

    public function delete(int $id): void
    {
        $document = Document::findOrFail($id);

        if ($document->file_path) {
            Storage::disk('local')->delete($document->file_path);
        }

        $document->delete();
    }

    public function toggleActive(int $id): void
    {
        $document = Document::findOrFail($id);
        $document->update(['is_active' => !$document->is_active]);
    }

    private function storeFile(UploadedFile $file): array
    {
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        $path = $file->store('documents', 'local');

        return [
            'file_path' => $path,
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
