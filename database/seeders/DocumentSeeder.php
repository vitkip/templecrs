<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first() ?? User::first();
        $uploaderId = $admin ? $admin->id : null;

        $depts = Department::all();
        $eduDept = $depts->where('name_en', 'Education Department')->first() ?? $depts->first();
        $finDept = $depts->where('name_en', 'Finance Department')->first() ?? $depts->first();
        $opDept  = $depts->where('name_en', 'Operations Department')->first() ?? $depts->first();

        $documents = [
            [
                'department_id' => $eduDept?->id,
                'uploaded_by' => $uploaderId,
                'title_lo' => 'ຂໍ້ຕົກລົງວ່າດ້ວຍການອະນຸມັດຫຼັກສູດການຮຽນ-ການສອນສົງ 2026',
                'title_en' => 'Decision on Approving Sangha Education Curriculum 2026',
                'doc_number' => '102/ກສ.2026',
                'category' => 'order',
                'description_lo' => 'ຂໍ້ຕົກລົງຢ່າງເປັນທາງການກ່ຽວກັບການຮັບຮອງ ແລະ ນຳໃຊ້ຫຼັກສູດການຮຽນການສອນໃໝ່ຂອງໂຮງຮຽນສົງທົ່ວປະເທດ.',
                'description_en' => 'Official decision regarding approval and implementation of the new teaching curriculum for Sangha schools nationwide.',
                'file_path' => 'documents/curriculum-approval-2026.pdf',
                'file_name' => 'curriculum-approval-2026.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 1024 * 1024 * 1.5, // 1.5 MB
                'issued_date' => Carbon::now()->subDays(15),
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'department_id' => $opDept?->id,
                'uploaded_by' => $uploaderId,
                'title_lo' => 'ແຈ້ງການກ່ຽວກັບການສະຫຼອງວັນວິສາຂະບູຊາ ປະຈຳປີ ພ.ສ 2569',
                'title_en' => 'Notification on Visakha Bucha Day Celebrations B.E. 2569',
                'doc_number' => '45/ອພສ.2026',
                'category' => 'announcement',
                'description_lo' => 'ຄຳແນະນຳ ແລະ ຕາຕະລາງການຈັດກິດຈະກຳທາງພຣະພຸດທະສາສະໜາ ໃນວັນວິສາຂະບູຊາ ໃຫ້ແກ່ວັດວາອາຮາມທົ່ວປະເທດ.',
                'description_en' => 'Guidelines and activity schedule for Buddhist rituals on Visakha Bucha Day for temples nationwide.',
                'file_path' => 'documents/visakha-bucha-2569.pdf',
                'file_name' => 'visakha-bucha-2569.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 1024 * 512, // 512 KB
                'issued_date' => Carbon::now()->subDays(3),
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'department_id' => $finDept?->id,
                'uploaded_by' => $uploaderId,
                'title_lo' => 'ລາຍງານລາຍຮັບ-ລາຍຈ່າຍ ງົບປະມານກອງທຶນພັດທະນາວັດ ປະຈຳໄຕມາດ 1',
                'title_en' => 'Financial Report of Temple Development Fund - Q1',
                'doc_number' => '12/ກງ.2026',
                'category' => 'report',
                'description_lo' => 'ບົດສະຫຼຸບບັນຊີລາຍຮັບ ແລະ ລາຍຈ່າຍ ຂອງກອງທຶນພັດທະນາວັດວາອາຮາມ ປະຈຳ 3 ເດືອນຕົ້ນປີ.',
                'description_en' => 'Summary of income and expenditures of the Temple Development Fund for the first quarter of the year.',
                'file_path' => 'documents/financial-report-q1-2026.xlsx',
                'file_name' => 'financial-report-q1-2026.xlsx',
                'file_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'file_size' => 1024 * 75, // 75 KB
                'issued_date' => Carbon::now()->subDays(20),
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'department_id' => $eduDept?->id,
                'uploaded_by' => $uploaderId,
                'title_lo' => 'ໂຄງການພັດທະນາສື່ການສອນ ພຣະພຸດທະສາສະໜາ ຜ່ານລະບົບອອນລາຍ',
                'title_en' => 'Online Buddhist Teaching Media Development Project',
                'doc_number' => '08/ກສ.ໂຄງການ',
                'category' => 'project',
                'description_lo' => 'ແຜນງານ ແລະ ຂັ້ນຕອນການຈັດຕັ້ງປະຕິບັດໂຄງການຜະລິດວິດີໂອ ແລະ ສື່ການສອນອອນລາຍ.',
                'description_en' => 'Workplan and implementation steps of the project producing online instructional videos and digital Dhamma media.',
                'file_path' => 'documents/online-media-project.docx',
                'file_name' => 'online-media-project.docx',
                'file_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'file_size' => 1024 * 250, // 250 KB
                'issued_date' => Carbon::now()->subDays(30),
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($documents as $doc) {
            Document::create($doc);
        }
    }
}
