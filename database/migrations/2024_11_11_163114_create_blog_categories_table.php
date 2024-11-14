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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();  // 自動增長ID
            $table->string('name');  // 分類名稱
            $table->string('slug')->unique();  // 用於URL的短語，應該是唯一的
            $table->text('description')->nullable();  // 分類的描述，對SEO和用戶有幫助
            $table->boolean('is_active')->default(true);  // 狀態，表示此分類是否為啟用狀態
            $table->unsignedBigInteger('parent_id')->nullable();  // 父類別ID（支持階層結構）
            $table->foreign('parent_id')->references('id')->on('blog_categories')->onDelete('cascade');  // 外鍵關聯至同一張表，用於階層分類
            $table->timestamps();  // 自動生成 created_at 和 updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
