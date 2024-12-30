<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_items', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // SEO-friendly URL
            $table->string('title'); // 文章標題
            $table->string('subtitle')->nullable();
            $table->string('category_id'); // 文章標題
            $table->integer('active')->nullable();
            $table->LONGTEXT('content'); // 文章內容
            $table->string('featured_image')->nullable(); // 首圖 URL
            $table->string('seo_title')->nullable(); // SEO 標題
            $table->text('seo_description')->nullable(); // SEO 描述
            $table->timestamp('published_at')->nullable(); // 發佈時間，允許為空
            $table->boolean('is_published')->default(false);
            $table->integer('orderby')->nullable();
            $table->timestamps();
            $table->softDeletes(); // 軟刪除
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_items');
    }
};
