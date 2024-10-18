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
        Schema::create('product_export_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_export_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('shelf_id');
            $table->string('unit');
            $table->integer('quantity');


            $table->foreign('product_export_id')->references('id')->on('product_exports')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('shelf_id')->references('id')->on('shelves')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_export_details');
    }
};
