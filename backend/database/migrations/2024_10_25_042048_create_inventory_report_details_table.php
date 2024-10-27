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
        Schema::create('inventory_report_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_report_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->unsignedBigInteger('shelf_id');
            $table->integer('actual_quantity')->nullable();
            $table->string('note')->nullable();

            $table->foreign('inventory_report_id')->references('id')->on('inventory_reports');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('shelf_id')->references('id')->on('shelves');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_report_details');
    }
};
