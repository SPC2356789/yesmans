<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Categories;

class BlogItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\BlogItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 隨機取一個分類作為 category_id
        $category = Categories::inRandomOrder()->first();

        return [
            'title' => $this->faker->sentence(), // 假標題
            'slug' => Str::slug($this->faker->unique()->sentence(3)), // 唯一 SEO Slug
            'category_id' => $category ? $category->id : null, // 關聯分類
            'featured_image' => $this->faker->imageUrl(1024, 1024, 'nature', true, 'Blog'), // 隨機圖像
            'seo_title' => $this->faker->sentence(), // SEO 標題
            'seo_description' => $this->faker->paragraph(), // SEO 描述
            'content' => $this->faker->paragraphs(5, true), // 假內容
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 years', '+1 month'), // 隨機發佈時間
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
