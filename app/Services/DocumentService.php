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
                Storage::disk('public')->delete($document->file_path);
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
            Storage::disk('public')->delete($document->file_path);
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

        $path = $file->store('documents', 'public');

        return [
            'file_path' => $path,
            'file_name' => $originalName,
            'file_type' => $mimeType,
            'file_size' => $size,
        ];
    }

    public function getStatistics(): array
    {
        return [
            'total'        => Document::count(),
            'active'       => Document::active()->count(),
            'this_month'   => Document::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'by_category'  => Document::selectRaw('category, count(*) as count')->groupBy('category')->pluck('count', 'category')->toArray(),
        ];
    }
}
