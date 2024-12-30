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
        Schema::create('index_carousels', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->nullable(); // 图片路径，允许为空
            $table->string('original_image_names'); // 原始照片名
            $table->string('alt'); // 原始照片名
            $table->boolean('status'); // 狀態
            $table->integer('orderby')->nullable();
            $table->softDeletes(); // 软删除
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('index_carousels');
    }
};
