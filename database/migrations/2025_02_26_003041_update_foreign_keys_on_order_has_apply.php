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
        Schema::table('order_has_apply', function (Blueprint $table) {
            // 先移除原有外鍵
            $table->dropForeign(['trip_order_on']);

            // 重新新增外鍵，加入 ON UPDATE CASCADE
            $table->foreign('trip_order_on')
                ->references('order_number')
                ->on('trip_orders')
                ->onUpdate('cascade') // 更新時同步更新
                ->onDelete('cascade'); // 刪除時同步刪除
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_has_apply', function (Blueprint $table) {
            // 回滾到原本的外鍵設定
            $table->dropForeign(['trip_order_on']);
            $table->foreign('trip_order_on')
                ->references('order_number')
                ->on('trip_orders')
                ->onDelete('cascade'); // 沒有 onUpdate
        });
    }
};
