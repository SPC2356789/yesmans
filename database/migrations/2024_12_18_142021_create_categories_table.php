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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('area');  // 分類名稱 文章為1，行程為2
            $table->string('type');  // 分類名稱 分類為1，標籤為2
            $table->string('name');  // 分類名稱
            $table->string('slug');
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_image')->nullable();
            $table->boolean('status')->default(false); // 狀態
            $table->integer('orderby')->nullable();
            $table->timestamps();
            $table->softDeletes();  // 添加软删除字段 deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
