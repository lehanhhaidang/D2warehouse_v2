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
        Schema::create('product_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->timestamp('receive_date');
            $table->integer('status')->nullable()->default(0);
            $table->string('note')->nullable();
            $table->unsignedBigInteger('propose_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('warehouse_id');
            $table->timestamps();

            $table->foreign('propose_id')->references('id')->on('proposes')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_receipts');
    }
};
