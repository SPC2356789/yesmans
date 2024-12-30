<?php

namespace Database\Factories;

use App\Models\BlogItem;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Overtrue\Pinyin\Pinyin;

class BlogItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BlogItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 隨機取一個分類作為 category_id
        $category = Categories::inRandomOrder()->first();

        // 生成 6 字的中文標題
        $title = $this->generateChineseText(6);

        // 生成 8 字的副標題
        $subtitle = $this->generateChineseText(8);

        // 使用拼音包將中文標題轉換為拼音並生成 slug
        $pinyin = new Pinyin();
        $slug = $pinyin->permalink($title, '-'); // 轉換為拼音的 slug

        return [
            'title' => $title, // 中文標題
            'subtitle' => $subtitle, // 中文副標題
            'slug' => $slug, // 中文轉換成拼音後的 slug
            'category_id' => $category ? $category->id : null, // 關聯分類
            'featured_image' => $this->faker->imageUrl(1024, 1024, 'nature', true, 'Blog'), // 隨機圖像
            'seo_title' => $this->faker->sentence(), // SEO 標題
            'seo_description' => $this->faker->paragraph(), // SEO 描述
            'content' => $this->generateChineseContent(1000), // 生成 1000 字的中文内容
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 years', '+1 month'), // 隨機發佈時間
            'is_published' => 1, // 隨機發佈時間
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * 生成隨機中文文字
     *
     * @param int $length
     * @return string
     */
    private function generateChineseText(int $length = 6)
    {
        $text = '';

        // 随机生成指定长度的汉字，范围在常见汉字的 Unicode 范围内（0x4E00 到 0x9FFF）
        for ($i = 0; $i < $length; $i++) {
            // 生成一个随机的 Unicode 编码点，范围是汉字的常见区域
            $unicode = rand(0x4E00, 0x9FFF);

            // 转换为 UTF-8 编码的字符
            $text .= mb_chr($unicode, 'UTF-8');
        }

        return $text;
    }

    private function generateChineseContent(int $length = 1000)
    {
        $content = '';

        // 不断拼接段落，直到达到要求的字数
        while (mb_strlen($content) < $length) {
            $content .= $this->generateChineseText(200);  // 每次生成 200 字
        }

        // 截取超过字数的部分
        return mb_substr($content, 0, $length);
    }

}
