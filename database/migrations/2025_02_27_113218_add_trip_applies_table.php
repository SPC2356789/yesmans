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
            $table->string('email_hash', 64)->comment('電子郵件雜湊值')->after('email');
            $table->string('phone_hash', 64)->comment('電話號碼雜湊值')->after('phone');
            $table->string('id_card_hash', 64)->comment('身分證號雜湊值')->after('phone');
            $table->string('PassPort_hash', 64)->nullable()->comment('護照號碼雜湊值')->after('PassPort');
            $table->string('emContactPh_hash', 64)->comment('緊急聯絡人電話雜湊值')->after('emContactPh');

            // 為雜湊欄位建立索引，提升查詢效率
            $table->index('email_hash');
            $table->index('phone_hash');
            $table->index('id_card_hash');
            $table->index('PassPort_hash');
            $table->index('emContactPh_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_applies', function (Blueprint $table) {
            $table->dropColumn([
                'email_hash',
                'phone_hash',
                'id_card_hash',
                'PassPort_hash',
                'emContactPh_hash',
            ]);
        });
    }

};
