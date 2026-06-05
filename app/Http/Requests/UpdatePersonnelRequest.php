<?php

namespace App\Http\Requests;

class UpdatePersonnelRequest extends StorePersonnelRequest
{
    /**
     * Same rules as StorePersonnelRequest.
     * Override only if update-specific rules needed.
     */
    public function rules(): array
    {
        $rules = parent::rules();

        // Photo is optional on update (keep existing if not provided)
        $rules['photo'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';

        return $rules;
    }
}
