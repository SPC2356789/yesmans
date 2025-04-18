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
        Schema::table('trip_applies', function (Blueprint $table) {
            $table->string('passport_pic', 64)->nullable()->comment('護照圖片')->after('PassPort_hash');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_applies', function (Blueprint $table) {
            $table->dropColumn([
                'passport_pic',
            ]);
        });
    }

};
