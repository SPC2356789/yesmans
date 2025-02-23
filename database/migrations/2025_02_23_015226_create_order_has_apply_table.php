<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_has_apply', function (Blueprint $table) {
            $table->string('trip_order_on'); // 指向 TripOrder 的 order_number
            $table->unsignedBigInteger('trip_apply_id');
            $table->timestamps();

            $table->foreign('trip_order_on')->references('order_number')->on('trip_orders')->onDelete('cascade');
            $table->foreign('trip_apply_id')->references('id')->on('trip_applies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public
    function down(): void
    {
        Schema::dropIfExists('order_has_apply');
    }
};
