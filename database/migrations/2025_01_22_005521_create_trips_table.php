<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable(); // 分類名稱
            $table->string('slug')->unique(); // SEO-friendly URL
            $table->string('title'); // 標題
            $table->string('subtitle')->nullable();
            $table->string('icon')->nullable();//圖標
            $table->string('carousel')->nullable(); // 輪播圖存成字串
            $table->string('tags')->nullable();//標籤
            $table->integer('quota')->nullable(); // 名額
            $table->text('description')->nullable(); // 介紹
            $table->decimal('amount', 10);//費用

            $table->longText('content'); // 內容
            $table->longText('agreement_content')->nullable(); // 同意書內容
            $table->boolean('is_published')->default(false);
            $table->integer('orderby')->nullable();
            $table->string('seo_title')->nullable(); // SEO 標題
            $table->text('seo_description')->nullable(); // SEO 描述
            $table->softDeletes(); // 軟刪除
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
