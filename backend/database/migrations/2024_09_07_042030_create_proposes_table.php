<?php

use App\ProposeStatus;
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
        Schema::create('proposes', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->enum('status', [
                ProposeStatus::PENDING_SEND->value,
                ProposeStatus::PENDING->value,
                ProposeStatus::APPROVED->value,
                ProposeStatus::REJECTED->value,
            ])->default(ProposeStatus::PENDING_SEND->value);
            $table->string('description');
            $table->unsignedbigInteger('warehouse_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposes');
    }
};
