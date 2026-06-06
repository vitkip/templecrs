<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title_lo' => 'ຍິນດີຕ້ອນຮັບເຂົ້າສູ່ ລະບົບຈັດການອົງການພຣະພຸດທະສາສະໜາ',
                'title_en' => 'Welcome to the Buddhist Organization Management System',
                'subtitle_lo' => 'ລະບົບຂໍ້ມູນຂ່າວສານ, ບຸກຄະລາກອນສົງ ແລະ ຄັງເອກະສານທາງການສຶກສາສົງ ທົ່ວປະເທດ',
                'subtitle_en' => 'Information portal, Sangha personnel records, and digital document library nationwide',
                'image_path' => 'hero-slides/temple1.png',
                'button_link' => '#news',
                'button_text_lo' => 'ຕິດຕາມຂ່າວສານ',
                'button_text_en' => 'Read Latest News',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title_lo' => 'ບໍລິຫານງານ ແລະ ເຜີຍແຜ່ສິນລະທຳ ດ້ວຍທຳມາພິບານ',
                'title_en' => 'Administration and Propagation of Dhamma with Governance',
                'subtitle_lo' => 'ສ້າງເສັ້ນທາງແຫ່ງປັນຍາ, ເສີມສ້າງສິລະທຳ ແລະ ທຳມະໃຫ້ແກ່ສັງຄົມລາວ',
                'subtitle_en' => 'Creating paths of wisdom, enhancing ethics and moral values for Lao society',
                'image_path' => 'hero-slides/temple2.png',
                'button_link' => '#documents',
                'button_text_lo' => 'ຄົ້ນຫາເອກະສານ',
                'button_text_en' => 'Browse Library',
                'sort_order' => 2,
                'is_active' => true,
            ]
        ];

        foreach ($slides as $data) {
            HeroSlide::create($data);
        }
    }
}
