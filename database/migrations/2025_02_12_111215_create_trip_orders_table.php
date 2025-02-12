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
        Schema::create('trip_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->comment('訂單編號');
            $table->string('trip_uuid')->comment('對應的開團資料');
            $table->string('applies')->comment('誰報名');
            $table->unsignedDecimal('amount', 10, 2)->default(0)->comment('訂單金額'); // 訂單總金額
            $table->string('paid_amount')->nullable()->comment('已匯款金額'); // 新增已匯款金額 以陣列存放
            $table->string('account_last_five')->nullable()->comment('帳號末五碼'); // 新增帳號末五碼 以陣列存放
            $table->tinyInteger('status')->default(0)->comment('訂單狀態');
            $table->timestamps();
            $table->softDeletes(); // Add the softDeletes column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_orders');
    }
};
