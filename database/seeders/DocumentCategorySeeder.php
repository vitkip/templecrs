<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'order',        'name_lo' => 'ຄຳສັ່ງ',     'name_en' => 'Order / Directive',  'icon' => 'gavel',             'color' => 'red',    'sort_order' => 1],
            ['slug' => 'announcement', 'name_lo' => 'ແຈ້ງການ',    'name_en' => 'Announcement',       'icon' => 'campaign',          'color' => 'blue',   'sort_order' => 2],
            ['slug' => 'certificate',  'name_lo' => 'ໃບຢັ້ງຢືນ', 'name_en' => 'Certificate',        'icon' => 'workspace_premium', 'color' => 'amber',  'sort_order' => 3],
            ['slug' => 'report',       'name_lo' => 'ລາຍງານ',     'name_en' => 'Report',             'icon' => 'assessment',        'color' => 'green',  'sort_order' => 4],
            ['slug' => 'project',      'name_lo' => 'ໂຄງການ',     'name_en' => 'Project Document',   'icon' => 'folder_special',    'color' => 'purple', 'sort_order' => 5],
            ['slug' => 'other',        'name_lo' => 'ອື່ນໆ',       'name_en' => 'Other',              'icon' => 'description',       'color' => 'gray',   'sort_order' => 6],
        ];

        foreach ($categories as $cat) {
            DocumentCategory::updateOrCreate(['slug' => $cat['slug']], array_merge($cat, ['is_active' => true]));
        }
    }
}
