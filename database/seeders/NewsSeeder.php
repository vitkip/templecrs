<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'super_admin')->first() ?? User::first();
        $authorId = $admin ? $admin->id : null;

        $newsItems = [
            [
                'author_id' => $authorId,
                'title_lo' => 'ພິທີມອບ-ຮັບ ໜ້າທີ່ການບໍລິຫານງານພຣະພຸດທະສາສະໜາແຂວງ',
                'title_en' => 'Handover Ceremony of Provincial Buddhist Administration',
                'excerpt_lo' => 'ພິທີມອບ-ຮັບ ໜ້າທີ່ຫົວໜ້າກຳມາທິການເຜີຍແຜ່ ແລະ ບໍລິຫານງານ ປະຈຳແຂວງ ຈັດຂຶ້ນຢ່າງສົມກຽດ.',
                'excerpt_en' => 'The official handover ceremony for the head of provincial propagation and administration commission was held with honors.',
                'content_lo' => 'ໃນວັນທີ 1 ມິຖຸນາ 2026 ຜ່ານມາ ໄດ້ມີພິທີຢ່າງເປັນທາງການໃນການມອບ-ຮັບ ໜ້າທີ່ການບໍລິຫານງານພຣະພຸດທະສາສະໜາ ລະຫວ່າງຄະນະເກົ່າ ແລະ ຄະນະໃໝ່ ເພື່ອສືບຕໍ່ວຽກງານພັດທະນາ ແລະ ເຜີຍແຜ່ສິນທຳ ໃຫ້ມີຄວາມກ້າວໜ້າ.',
                'content_en' => 'On June 1, 2026, an official ceremony was held for the handover of responsibilities of the provincial Buddhist administration between the outgoing and incoming committees to continue development and propagation of Dhamma.',
                'cover_image' => null,
                'published_at' => Carbon::now()->subDays(2),
                'is_featured' => true,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'author_id' => $authorId,
                'title_lo' => 'ໂຄງການອົບຮົມສິລະທຳ ແລະ ຄຸນນະທຳ ສຳລັບເຍົາວະຊົນ ປະຈຳປີ 2026',
                'title_en' => 'Annual Youth Dhamma and Ethics Training Project 2026',
                'excerpt_lo' => 'ກຳມາທິການສຶກສາສົງ ໄດ້ຈັດກິດຈະກຳອົບຮົມສິນລະທຳແກ່ນັກຮຽນ ໃນໄລຍະພັກແລ້ງ ເພື່ອເສີມສ້າງຄຸນນະທຳ.',
                'excerpt_en' => 'The Sangha Education Commission organized a Dhamma training for students during summer break to cultivate ethical values.',
                'content_lo' => 'ເພື່ອແນໃສ່ຫຼໍ່ຫຼອມຈິດໃຈຂອງເຍົາວະຊົນລາວ ໃຫ້ມີຄຸນນະທຳ, ຄວາມກະຕັນຍູ ແລະ ລະບຽບວິນຍານ, ກຳມາທິການສຶກສາສົງຈຶ່ງໄດ້ຈັດໂຄງການຝຶກອົບຮົມນີ້ຂຶ້ນ ໂດຍມີນັກຮຽນເຂົ້າຮ່ວມກວ່າ 150 ຄົນ.',
                'content_en' => 'Aiming to shape the minds of Lao youth with moral values, gratitude, and discipline, the Sangha Education Commission organized this training program with over 150 student participants.',
                'cover_image' => null,
                'published_at' => Carbon::now()->subDays(5),
                'is_featured' => false,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'author_id' => $authorId,
                'title_lo' => 'ພິທີສົງເຄາະ ແລະ ມອບປັດໄຈຊ່ວຍເຫຼືອ ຜູ້ປະສົບໄພພິບັດທາງທຳມະຊາດ',
                'title_en' => 'Charity and Relief Distribution for Natural Disaster Victims',
                'excerpt_lo' => 'ຄະນະສົງຮ່ວມກັບພາກສ່ວນກ່ຽວຂ້ອງ ມອບເຄື່ອງອຸປະໂພກບໍລິໂພກ ຊ່ວຍເຫຼືອປະຊາຊົນທີ່ໄດ້ຮັບຜົນກະທົບຈາກໄພນ້ຳຖ້ວມ.',
                'excerpt_en' => 'The Sangha, in coordination with relevant sectors, distributed consumer goods to assist people affected by recent floods.',
                'content_lo' => 'ດ້ວຍຄວາມເມດຕາທຳ, ທາງອົງການພຣະພຸດທະສາສະໜາ ໄດ້ຈັດຕັ້ງກອງທຶນສົງເຄາະ ເພື່ອນຳເອົາເຂົ້າສານ, ອາຫານແຫ້ງ ແລະ ປັດໄຈຈຳເປັນ ໄປມອບໃຫ້ປະຊາຊົນທີ່ປະສົບອຸທົກກະໄພ ເພື່ອບັນເທົາທຸກໃນເບື້ອງຕົ້ນ.',
                'content_en' => 'Out of compassion, the Buddhist Organization established a relief fund to bring rice, dry food, and essential supplies to citizens affected by floods to alleviate their immediate difficulties.',
                'cover_image' => null,
                'published_at' => Carbon::now()->subDays(10),
                'is_featured' => false,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'author_id' => $authorId,
                'title_lo' => 'ການປະຊຸມສະຫຼຸບວຽກງານເຜີຍແຜ່ພຣະພຸດທະສາສະໜາ ທົ່ວປະເທດ',
                'title_en' => 'National Buddhist Propagation Performance Review Meeting',
                'excerpt_lo' => 'ກອງປະຊຸມໃຫຍ່ ສະຫຼຸບຜົນງານການຈັດຕັ້ງປະຕິບັດວຽກງານເຜີຍແຜ່ ປະຈຳສົກປີຜ່ານມາ.',
                'excerpt_en' => 'The general assembly evaluated the implementation results of the propagation works over the past academic year.',
                'content_lo' => 'ກອງປະຊຸມໄດ້ສຸມໃສ່ການປະເມີນຜົນການເຜີຍແຜ່ທັມມະ, ການນຳໃຊ້ສື່ເຕັກໂນໂລຊີເຂົ້າໃນການສອນສິນລະທຳ ແລະ ການວາງແຜນຍຸດທະສາດສຳລັບສົກປີໜ້າ ໃຫ້ມີປະສິດທິຜົນສູງຂຶ້ນ.',
                'content_en' => 'The meeting focused on evaluating Dhamma propagation, utilization of technology media in ethics instruction, and formulating strategic plans for the next year for higher efficiency.',
                'cover_image' => null,
                'published_at' => Carbon::now()->subDays(15),
                'is_featured' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($newsItems as $news) {
            News::create($news);
        }
    }
}
