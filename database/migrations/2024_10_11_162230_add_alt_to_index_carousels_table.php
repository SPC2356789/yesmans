<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAltToIndexCarouselsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('index_carousels', function (Blueprint $table) {
            $table->string('alt')->nullable()->after('image_path'); // 添加 alt 列
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('index_carousels', function (Blueprint $table) {
            $table->dropColumn('alt'); // 删除 alt 列
        });
    }
}
