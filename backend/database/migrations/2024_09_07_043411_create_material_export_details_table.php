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
        Schema::create('material_export_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_export_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('shelf_id');
            $table->string('unit');
            $table->integer('quantity');

            $table->foreign('shelf_id')->references('id')->on('shelves')->onDelete('cascade');
            $table->foreign('material_export_id')->references('id')->on('material_exports')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_export_details');
    }
};
