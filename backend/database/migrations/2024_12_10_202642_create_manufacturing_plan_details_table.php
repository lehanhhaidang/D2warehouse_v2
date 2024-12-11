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
        Schema::create('manufacturing_plan_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manufacturing_plan_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_quantity');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('material_quantity');


            $table->foreign('manufacturing_plan_id')->references('id')->on('manufacturing_plans')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_plan_details');
    }
};
