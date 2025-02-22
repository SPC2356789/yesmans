<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * 增加行程提示
     */
    public function up(): void
    {
        // 修改 `trips` 表
        Schema::table('trips', function (Blueprint $table) {
            $table->boolean('passport_enabled')->default(false)->after('quota'); // 是否開啟護照功能
            $table->string('hintMonth')->nullable()->after('quota'); // 儲存月份（用字串）

        });

        //  修改 `trip_times` 表
        Schema::table('trip_times', function (Blueprint $table) {
            $table->boolean('passport_enabled')->default(false)->after('quota'); // 是否開啟護照功能
            $table->string('hintMonth')->nullable()->after('quota'); // 儲存月份，放在 id 之後
        });
    }

    public function down(): void
    {
        // 回滾時刪除欄位
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('hintMonth');
        });

        Schema::table('trip_times', function (Blueprint $table) {
            $table->dropColumn('hintMonth');
        });
    }
};
