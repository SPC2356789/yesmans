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
            $table->unsignedBigInteger('mould_id')->index()->comment('關聯 Trip 模型 ID'); // 加索引
            $table->decimal('amount', 10, 2)->default(0)->comment('行程金額'); // 限制小數點 & 預設值
            $table->date('date_start')->nullable()->comment('行程開始日期');
            $table->date('date_end')->nullable()->comment('行程結束日期');
            $table->unsignedInteger('quota')->default(0)->comment('報名名額上限');
            $table->unsignedInteger('applied_count')->default(0)->comment('已報名人數');
            $table->text('agreement_content')->nullable()->comment('報名同意書內容');
            $table->boolean('food')->default(false)->comment('是否開啟飲食需求');
            $table->boolean('is_published')->default(true)->comment('是否公開顯示');
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
