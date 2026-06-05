<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name_lo' => 'ພະແນກການສຶກສາ',
                'name_en' => 'Education Department',
                'description_lo' => 'ພະແນກຮັບຜິດຊອບການສຶກສາ ແລະ ການຝຶກອົບຮົມ',
                'description_en' => 'Department responsible for education and training programs',
                'sort_order' => 1,
            ],
            [
                'name_lo' => 'ພະແນກບໍລິຫານ',
                'name_en' => 'Operations Department',
                'description_lo' => 'ພະແນກບໍລິຫານ ແລະ ປະສານງານ',
                'description_en' => 'Department responsible for operations and coordination',
                'sort_order' => 2,
            ],
            [
                'name_lo' => 'ພະແນກການເງິນ',
                'name_en' => 'Finance Department',
                'description_lo' => 'ພະແນກການເງິນ ແລະ ບັນຊີ',
                'description_en' => 'Department responsible for finance and accounting',
                'sort_order' => 3,
            ],
            [
                'name_lo' => 'ພະແນກພົວພັນສາທາລະນະ',
                'name_en' => 'Public Relations Department',
                'description_lo' => 'ພະແນກພົວພັນສາທາລະນະ ແລະ ສື່ສານ',
                'description_en' => 'Department responsible for public relations and communications',
                'sort_order' => 4,
            ],
            [
                'name_lo' => 'ພະແນກຄຸ້ມຄອງຊັບສິນ',
                'name_en' => 'Asset Management Department',
                'description_lo' => 'ພະແນກຄຸ້ມຄອງຊັບສິນ ແລະ ການກໍ່ສ້າງ',
                'description_en' => 'Department responsible for asset management and construction',
                'sort_order' => 5,
            ],
            [
                'name_lo' => 'ພະແນກໄອທີ',
                'name_en' => 'IT Department',
                'description_lo' => 'ພະແນກເທັກໂນໂລຢີ ແລະ ລະບົບຂໍ້ມູນ',
                'description_en' => 'Department responsible for technology and information systems',
                'sort_order' => 6,
            ],
            [
                'name_lo' => 'ພະແນກທັມມະ',
                'name_en' => 'Dhamma Department',
                'description_lo' => 'ພະແນກເຜີຍແຜ່ ແລະ ສອນທັມ',
                'description_en' => 'Department responsible for Dhamma propagation and teaching',
                'sort_order' => 7,
            ],
            [
                'name_lo' => 'ພະແນກສະຫວັດດີການ',
                'name_en' => 'Welfare Department',
                'description_lo' => 'ພະແນກສະຫວັດດີການ ແລະ ການບໍລິການສັງຄົມ',
                'description_en' => 'Department responsible for welfare and social services',
                'sort_order' => 8,
            ],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
