<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Organization info
            ['key' => 'org_name_lo',           'value' => 'ອົງການພຣະພຸດທະສາສະໜາລາວ',     'group' => 'organization'],
            ['key' => 'org_name_en',           'value' => 'Lao Buddhist Organization',      'group' => 'organization'],
            ['key' => 'org_address_lo',        'value' => '',                               'group' => 'organization'],
            ['key' => 'org_address_en',        'value' => '',                               'group' => 'organization'],
            ['key' => 'org_phone',             'value' => '',                               'group' => 'organization'],
            ['key' => 'org_email',             'value' => '',                               'group' => 'organization'],
            ['key' => 'org_website',           'value' => '',                               'group' => 'organization'],
            ['key' => 'org_established_year',  'value' => '',                               'group' => 'organization'],
            ['key' => 'org_logo_url',          'value' => '',                               'group' => 'organization'],

            // System preferences
            ['key' => 'default_locale',        'value' => 'lo',                             'group' => 'system'],
            ['key' => 'per_page',              'value' => '15',                             'group' => 'system'],
            ['key' => 'show_english_names',    'value' => '1',                              'group' => 'system'],
        ];

        foreach ($defaults as $item) {
            Setting::firstOrCreate(['key' => $item['key']], $item);
        }
    }
}
