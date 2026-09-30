<?php

namespace App\Services;

use App\Models\HeroSlide;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HeroSlideService
{
    protected ?ImageOptimizerService $optimizer = null;

    protected function getOptimizer(): ImageOptimizerService
    {
        return $this->optimizer ??= app(ImageOptimizerService::class);
    }

    public function create(array $data, UploadedFile $image): HeroSlide
    {
        $data['image_path'] = $this->getOptimizer()->optimizeAndStore($image, 'hero-slides', 1920, null, 84);

        return HeroSlide::create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $image = null): HeroSlide
    {
        $slide = HeroSlide::findOrFail($id);

        if ($image) {
            // Delete old image file
            if ($slide->image_path) {
                Storage::disk('public')->delete($slide->image_path);
            }
            $data['image_path'] = $this->getOptimizer()->optimizeAndStore($image, 'hero-slides', 1920, null, 84);
        }

        $slide->update($data);

        return $slide->fresh();
    }

    public function delete(int $id): void
    {
        $slide = HeroSlide::findOrFail($id);

        if ($slide->image_path) {
            Storage::disk('public')->delete($slide->image_path);
        }

        $slide->delete();
    }

    public function toggleActive(int $id): void
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->update(['is_active' => !$slide->is_active]);
    }

    public function getStatistics(): array
    {
        return [
            'total'  => HeroSlide::count(),
            'active' => HeroSlide::active()->count(),
        ];
    }
}
