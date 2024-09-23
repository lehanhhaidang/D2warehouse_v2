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
        Schema::create('material_receipt_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_receipt_id');
            $table->unsignedBigInteger('material_id');
            $table->string('unit');
            $table->integer('quantity');


            $table->foreign('material_receipt_id')->references('id')->on('material_receipts')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_receipt_details');
    }
};
