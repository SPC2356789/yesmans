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
        Schema::create('trip_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mould_id'); // 關聯模型 ID
            $table->decimal('amount', 10); // 金額
            $table->date('date_start')->nullable(); // 日期
            $table->date('date_end')->nullable(); // 日期
            $table->integer('quota'); // 名額
            $table->text('agreement_content')->nullable(); // 同意書規範
            $table->boolean('food')->default(false);//是否開啟飲食習慣
            $table->boolean('is_published')->default(true);
            $table->softDeletes(); // 軟刪除
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_times');
    }
};
