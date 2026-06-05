<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonnelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Will be restricted by policy/permission later
    }

    public function rules(): array
    {
        $rules = [
            // Person Type
            'gender' => 'required|in:monk,male,female',

            // Name — Bilingual
            'first_name_lo' => 'nullable|string|max:100',
            'first_name_en' => 'nullable|string|max:100',
            'last_name_lo'  => 'nullable|string|max:100',
            'last_name_en'  => 'nullable|string|max:100',
            'name_lo'       => 'required|string|max:200',
            'name_en'       => 'nullable|string|max:200',

            // Title / Honorific
            'title_lo' => 'nullable|string|max:100',
            'title_en' => 'nullable|string|max:100',

            // Position
            'position_lo' => 'required|string|max:200',
            'position_en' => 'nullable|string|max:200',

            // Department
            'department_id' => 'nullable|exists:departments,id',

            // Location
            'birth_village_lo' => 'nullable|string|max:200',
            'birth_village_en' => 'nullable|string|max:200',
            'district_lo'     => 'nullable|string|max:100',
            'district_en'     => 'nullable|string|max:100',
            'province_lo'     => 'nullable|string|max:100',
            'province_en'     => 'nullable|string|max:100',

            // Contact & Social
            'email'    => 'nullable|email|max:120',
            'phone'    => 'nullable|string|max:50',
            'facebook' => 'nullable|url|max:300',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // Biography & Education
            'bio_lo'       => 'nullable|string',
            'bio_en'       => 'nullable|string',
            'education_lo' => 'nullable|string|max:300',
            'education_en' => 'nullable|string|max:300',

            // Personal Info
            'date_of_birth' => 'nullable|date|before:today',

            // Term
            'term_start' => 'nullable|digits:4',
            'term_end'   => 'nullable|digits:4|gte:term_start',

            // Display Control
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ];

        // Monk-specific fields — only validated when gender is monk
        if ($this->input('gender') === 'monk') {
            $rules['current_temple_lo']  = 'nullable|string|max:300';
            $rules['current_temple_en']  = 'nullable|string|max:300';
            $rules['date_of_ordination'] = 'nullable|date|before_or_equal:today';
            $rules['pansa']              = 'nullable|integer|min:0|max:100';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name_lo.required'     => 'ກະລຸນາໃສ່ຊື່ເຕັມ (ພາສາລາວ) / Full name in Lao is required.',
            'position_lo.required' => 'ກະລຸນາໃສ່ຕຳແໜ່ງ (ພາສາລາວ) / Position in Lao is required.',
            'gender.required'      => 'ກະລຸນາເລືອກປະເພດບຸກຄົນ / Please select person type.',
            'gender.in'            => 'ປະເພດບຸກຄົນບໍ່ຖືກຕ້ອງ / Invalid person type.',
        ];
    }
}
