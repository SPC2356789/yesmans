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
        Schema::create('trip_applies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('使用者名稱');
            $table->string('order_number')->comment('訂單編號');
            $table->string('gender')->comment('性別');
            $table->date('birthday')->comment('生日');
            $table->string('email')->comment('電子郵件，唯一');
            $table->string('phone')->comment('電話號碼');
            $table->string('country')->comment('國家代碼');
            $table->string('id_card')->comment('身分證號，唯一');
            $table->string('address')->comment('地址');
            $table->string('PassPort')->nullable()->comment('護照號碼，唯一');
            $table->string('diet')->comment('飲食偏好');
            $table->string('experience')->nullable()->comment('戶外經驗');
            $table->string('disease')->nullable()->comment('病史，選填');
            $table->string('LINE')->nullable()->comment('LINE 聯絡 ID');
            $table->string('IG')->nullable()->comment('Instagram 帳號');
            $table->string('emContactPh')->comment('緊急聯絡人電話');
            $table->string('emContact')->comment('緊急聯絡人名稱與關係');
            $table->timestamps();
            $table->softDeletes(); // Add the softDeletes column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_applies');
    }
};
