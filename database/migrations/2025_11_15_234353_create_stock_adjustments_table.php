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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->dateTime('adjustment_date');
            $table->foreignId('uom_id')->constrained()->cascadeOnDelete();
            $table->decimal('adjustment_qty', 20, 2)->default(0);
            $table->string('adjustment_reason');
            $table->string('adjustment_type');
            $table->decimal('adjustment_price', 20, 2)->default(0);
            $table->decimal('qty_out', 20, 2)->default(0);
            $table->decimal('remain_qty', 20, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
