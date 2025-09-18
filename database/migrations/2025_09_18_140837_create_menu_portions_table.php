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
        Schema::create('menu_portions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained('productions')->onDelete('cascade');
            $table->foreignId('menu_type_id')->constrained('menu_types')->onDelete('cascade');
            $table->integer('portion_count')->default(0);
            $table->integer('budget_cost')->default(0);
            $table->integer('total_budget_cost')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->integer('total_estimated_cost')->default(0);
            $table->integer('total_actual_cost')->default(0); 
            $table->integer('estimated_cost_per_portion')->default(0);
            $table->integer('actual_cost_per_portion')->default(0);  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_portions');
    }
};
