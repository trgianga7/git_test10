<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dia_chi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_khach_hang')->constrained('khach_hang')->cascadeOnDelete();
            $table->integer('tinh');
            $table->integer('huyen');
            $table->string('phuong');
            $table->string('dia_chi');
            $table->boolean('trang_thai')->default(0);
            $table->date('ngay_tao');
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dia_chi');
    }
};