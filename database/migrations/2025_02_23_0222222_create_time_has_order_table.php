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
        Schema::create('time_has_order', function (Blueprint $table) {
            $table->unsignedBigInteger('trip_order_id'); // 假設 trip_orders.trip_uuid 是 VARCHAR(36)
            $table->string('trip_times_uuid', 36); // 假設 trip_times.uuid 是 VARCHAR(36)
            $table->timestamps();

            $table->foreign('trip_order_id')->references('id')->on('trip_orders')->onDelete('cascade');
            $table->foreign('trip_times_uuid')->references('uuid')->on('trip_times')->onDelete('cascade');
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
