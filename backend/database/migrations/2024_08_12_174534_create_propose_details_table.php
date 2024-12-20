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
        Schema::create('propose_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('propose_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->string('unit');
            $table->integer('quantity');
            $table->string('note')->nullable();

            $table->timestamps();

            $table->foreign('propose_id')->references('id')->on('proposes')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propose_details');
    }
};
