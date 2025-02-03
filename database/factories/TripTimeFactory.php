<?php

namespace Database\Factories;

use App\Models\TripTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TripTime>
 */
class TripTimeFactory extends Factory
{
    protected $model = TripTime::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // 确保开始日期在未来四个月内
        $startDate = $this->faker->dateTimeBetween('now', '+4 months')->format('Y-m-d');

// 确保结束日期在开始日期的 3 天内
        $endDate = $this->faker->dateTimeBetween($startDate . ' 00:00:00', $startDate . ' +3 days')->format('Y-m-d');
        return [
            'uuid' => (string)Str::uuid(),  // 確保 `uuid` 有值
            'mould_id' => $this->faker->randomElement([1, 4, 5, 6, 7, 8, 9]), // 隨機挑選一個mould_id
            'amount' => $this->faker->numberBetween(2000, 6000), // 隨機生成100到10000之間的整數
            'date_start' => $startDate,
            'date_end' => $endDate,
            'quota' => $this->faker->numberBetween(8, 16), // 隨機生成名額
            'agreement_content' => '同意書規範', // 隨機生成同意書內容
            'food' => ($startDate == $endDate) ? 0 : 1, // 隨機生成是否開啟飲食選項
            'is_published' => 1, // 隨機生成是否發佈
        ];
    }
}
