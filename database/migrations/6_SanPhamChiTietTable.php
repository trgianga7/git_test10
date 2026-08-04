<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('san_pham_chi_tiet', function (Blueprint $table) {
            $table->id();
            $table->integer('id_san_pham')->index();
            $table->uuid('ma_sp')->unique();
            $table->string('anh_dai_dien')->nullable();
            $table->string('ten_phu')->nullable()->index();
            $table->text('mo_ta')->nullable();
            $table->unsignedBigInteger('gia_ban')->index();
            $table->unsignedBigInteger('gia_khuyen_mai')->nullable()->index();
            $table->string('khuyen_mai')->nullable();
            $table->integer('so_luong');
            $table->boolean('trang_thai')->default(0);
            //$table->integer('id_nguoi_dung')->nullable()->index();
            $table->date('ngay_tao');
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('san_pham_chi_tiet');
    }
};