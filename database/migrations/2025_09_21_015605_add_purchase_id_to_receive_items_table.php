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
        Schema::table('receive_items', function (Blueprint $table) {
            $table->foreignId('purchase_id')->after('receive_id')->constrained();
            $table->foreignId('purchase_item_id')->after('purchase_id')->constrained();
            $table->date('expired_date')->after('receive_price')->nullable();
            $table->decimal('qty_out',24,2)->after('expired_date')->default(0);
            $table->decimal('remain_qty',24,2)->after('qty_out')->default(0);
            $table->decimal('invoiced_qty',24,2)->after('remain_qty')->default(0);
            $table->string('note')->after('invoiced_qty')->nullable();
            $table->string('status')->default('open');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receive_items', function (Blueprint $table) {
            //
        });
    }
};
