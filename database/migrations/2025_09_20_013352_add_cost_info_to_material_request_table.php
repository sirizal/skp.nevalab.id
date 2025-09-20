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
        Schema::table('material_requests', function (Blueprint $table) {
            $table->decimal('total_estimated_cost', 15, 2)->default(0)->after('standard_price');
            $table->decimal('total_actual_cost', 15, 2)->default(0)->after('total_estimated_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_requests', function (Blueprint $table) {
            //
        });
    }
};
