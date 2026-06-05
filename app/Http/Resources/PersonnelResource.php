<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PersonnelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->get('lang', app()->getLocale());

        return [
            'id'       => $this->id,
            'gender'   => $this->gender,
            'is_monk'  => $this->isMonk(),

            // Localized display fields
            'name'     => $locale === 'lo' ? $this->name_lo : ($this->name_en ?? $this->name_lo),
            'title'    => $locale === 'lo' ? $this->title_lo : ($this->title_en ?? $this->title_lo),
            'position' => $locale === 'lo' ? $this->position_lo : ($this->position_en ?? $this->position_lo),
            'bio'      => $locale === 'lo' ? $this->bio_lo : ($this->bio_en ?? $this->bio_lo),

            // Raw bilingual fields (for admin / edit)
            'name_lo'      => $this->name_lo,
            'name_en'      => $this->name_en,
            'title_lo'     => $this->title_lo,
            'title_en'     => $this->title_en,
            'position_lo'  => $this->position_lo,
            'position_en'  => $this->position_en,

            // First / Last names
            'first_name_lo' => $this->first_name_lo,
            'first_name_en' => $this->first_name_en,
            'last_name_lo'  => $this->last_name_lo,
            'last_name_en'  => $this->last_name_en,

            // Location
            'birth_village_lo' => $this->birth_village_lo,
            'birth_village_en' => $this->birth_village_en,
            'district_lo'     => $this->district_lo,
            'district_en'     => $this->district_en,
            'province_lo'     => $this->province_lo,
            'province_en'     => $this->province_en,

            // Monk-only (null for laypersons)
            'current_temple' => $this->when($this->isMonk(), [
                'lo' => $this->current_temple_lo,
                'en' => $this->current_temple_en,
            ]),
            'date_of_ordination' => $this->when($this->isMonk(), $this->date_of_ordination),
            'pansa'              => $this->when($this->isMonk(), $this->pansa),

            // Contact
            'photo_url' => $this->photo_url ? Storage::url($this->photo_url) : null,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'facebook'  => $this->facebook,

            // Bio & Education
            'bio_lo'       => $this->bio_lo,
            'bio_en'       => $this->bio_en,
            'education_lo' => $this->education_lo,
            'education_en' => $this->education_en,

            // Date
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),

            // Relationships
            'department' => new DepartmentResource($this->whenLoaded('department')),

            // Term
            'term_start' => $this->term_start,
            'term_end'   => $this->term_end,

            // Meta
            'sort_order' => $this->sort_order,
            'is_active'  => $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
