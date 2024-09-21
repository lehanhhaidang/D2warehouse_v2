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
            $table->integer('unit');
            $table->integer('quantity');
            $table->timestamps();
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
