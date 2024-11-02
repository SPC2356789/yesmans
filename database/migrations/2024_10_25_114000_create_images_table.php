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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('disk'); // 存储磁盘
            $table->string('mime_type'); // MIME类型
            $table->string('file_extension'); // 文件扩展名
            $table->integer('width'); // 宽度
            $table->integer('height'); // 高度
            $table->integer('size'); // 文件大小
            $table->string('title')->nullable(); // 标题，可为空
            $table->string('alt')->nullable(); // 替代文本，可为空
            $table->uuid('uuid')->unique(); // UUID，确保唯一性
            $table->timestamps();
        });
        Schema::create('image_conversions', function (Blueprint $table) {
            $table->id();
//            $table->uuid('image_id');
            $table->string('conversion_name'); // 转换名称
            $table->string('conversion_md5'); // 转换的 MD5 值
            $table->integer('width'); // 图片宽度
            $table->integer('height'); // 图片高度
            $table->integer('size'); // 图片大小（单位：字节）
            $table->foreignId('image_id')->constrained()->onDelete('cascade'); // 外键，引用 images 表
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_conversions');
        Schema::dropIfExists('images');
    }
};
