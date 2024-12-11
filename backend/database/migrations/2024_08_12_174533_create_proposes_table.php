<?php

use App\Enum\ProposeStatus;
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
            $table->integer('status')->default(0); //0: Chờ gửi, 1: Chờ duyệt, 2: Đã duyệt, 3: Đã từ chối
            $table->string('description');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('manufacturing_plan_id')->nullable();
            $table->unsignedbigInteger('warehouse_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('manufacturing_plan_id')->references('id')->on('manufacturing_plans');
            $table->foreign('assigned_to')->references('id')->on('users');
            $table->foreign('order_id')->references('id')->on('orders');
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
