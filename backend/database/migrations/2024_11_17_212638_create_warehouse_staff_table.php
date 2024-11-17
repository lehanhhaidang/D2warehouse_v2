<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseStaffTable extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->timestamp('assigned_at')->nullable(); // Ngày giờ gán nhân viên vào kho
            $table->timestamps(); // created_at, updated_at

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');

            // Đảm bảo mỗi nhân viên chỉ gắn 1 lần cho cùng 1 kho
            $table->unique(['user_id', 'warehouse_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_staff');
    }
}
