<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->get('lang', app()->getLocale());

        return [
            'id'          => $this->id,
            'name'        => $locale === 'lo' ? $this->name_lo : ($this->name_en ?? $this->name_lo),
            'name_lo'     => $this->name_lo,
            'name_en'     => $this->name_en,
            'description' => $locale === 'lo' ? $this->description_lo : ($this->description_en ?? $this->description_lo),
            'description_lo' => $this->description_lo,
            'description_en' => $this->description_en,
            'head'        => new PersonnelResource($this->whenLoaded('head')),
            'personnel_count' => $this->when(isset($this->personnel_count), $this->personnel_count),
            'sort_order'  => $this->sort_order,
            'is_active'   => $this->is_active,
        ];
    }
}
