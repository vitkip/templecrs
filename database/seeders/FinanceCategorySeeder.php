<?php

namespace Database\Seeders;

use App\Models\FinanceCategory;
use Illuminate\Database\Seeder;

class FinanceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // ───── Income ─────
            ['type' => 'income', 'name_lo' => 'ການບໍລິຈາກ', 'name_en' => 'Donations', 'icon' => 'volunteer_activism', 'color' => 'green', 'sort_order' => 1],
            ['type' => 'income', 'name_lo' => 'ອຸປະຖຳ / ທານ', 'name_en' => 'Alms / Offerings', 'icon' => 'favorite', 'color' => 'emerald', 'sort_order' => 2],
            ['type' => 'income', 'name_lo' => 'ລາຍໄດ້ຈາກກິດຈະກຳ', 'name_en' => 'Event Income', 'icon' => 'event', 'color' => 'teal', 'sort_order' => 3],
            ['type' => 'income', 'name_lo' => 'ຄ່າຝຶກອົບຮົມ / ຫຼັກສູດ', 'name_en' => 'Training Fees', 'icon' => 'school', 'color' => 'cyan', 'sort_order' => 4],
            ['type' => 'income', 'name_lo' => 'ເງິນສະໜັບສະໜູນ', 'name_en' => 'Grants / Support', 'icon' => 'handshake', 'color' => 'blue', 'sort_order' => 5],
            ['type' => 'income', 'name_lo' => 'ລາຍໄດ້ອື່ນໆ', 'name_en' => 'Other Income', 'icon' => 'add_circle', 'color' => 'indigo', 'sort_order' => 6],

            // ───── Expense ─────
            ['type' => 'expense', 'name_lo' => 'ຄ່ານ້ຳ-ໄຟ', 'name_en' => 'Utilities', 'icon' => 'bolt', 'color' => 'yellow', 'sort_order' => 1],
            ['type' => 'expense', 'name_lo' => 'ຄ່າສ່ອມແປງ / ບຳລຸງຮັກສາ', 'name_en' => 'Maintenance', 'icon' => 'build', 'color' => 'orange', 'sort_order' => 2],
            ['type' => 'expense', 'name_lo' => 'ຄ່າອາຫານ / ສັງຄະທານ', 'name_en' => 'Food / Offerings', 'icon' => 'restaurant', 'color' => 'red', 'sort_order' => 3],
            ['type' => 'expense', 'name_lo' => 'ຄ່າຊື້ຂອງ / ວັດສະດຸ', 'name_en' => 'Purchases', 'icon' => 'shopping_cart', 'color' => 'pink', 'sort_order' => 4],
            ['type' => 'expense', 'name_lo' => 'ຄ່າເດີນທາງ', 'name_en' => 'Travel', 'icon' => 'directions_car', 'color' => 'purple', 'sort_order' => 5],
            ['type' => 'expense', 'name_lo' => 'ຄ່າຈ້າງ / ເງີນເດືອນ', 'name_en' => 'Wages / Stipends', 'icon' => 'payments', 'color' => 'violet', 'sort_order' => 6],
            ['type' => 'expense', 'name_lo' => 'ຄ່າໃຊ້ຈ່າຍກິດຈະກຳ', 'name_en' => 'Event Expenses', 'icon' => 'celebration', 'color' => 'rose', 'sort_order' => 7],
            ['type' => 'expense', 'name_lo' => 'ລາຍຈ່າຍອື່ນໆ', 'name_en' => 'Other Expenses', 'icon' => 'more_horiz', 'color' => 'slate', 'sort_order' => 8],
        ];

        foreach ($categories as $cat) {
            FinanceCategory::firstOrCreate(
                ['type' => $cat['type'], 'name_lo' => $cat['name_lo']],
                $cat
            );
        }
    }
}
