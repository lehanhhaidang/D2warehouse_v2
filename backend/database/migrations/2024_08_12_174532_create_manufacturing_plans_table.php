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
        Schema::create('manufacturing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->integer('status')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            //  0: Chờ duyệt
            //  1: Đã duyệt
            //  2: Đã từ chối
            //  3: Đã xuất nguyên vật liệu
            //  4: Đang sản xuất
            //  5: Đã hoàn thành
            $table->softDeletes();
            $table->unsignedBigInteger('begin_manufacturing_by')->nullable();
            $table->unsignedBigInteger('finish_manufacturing_by')->nullable();


            $table->foreign('begin_manufacturing_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('finish_manufacturing_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_plans');
    }
};
