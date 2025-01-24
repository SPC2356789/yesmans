<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogItem;

class BlogItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 使用 Factory 生成 10 條 BlogItem 假資料
//        BlogItem::factory()->count(100)->create(); // 可以根據需要修改數量
    }
}
