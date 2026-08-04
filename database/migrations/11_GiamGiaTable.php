<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('giam_gia', function (Blueprint $table) {
            $table->id();
            $table->string('ten_giam_gia')->unique();
            $table->boolean('loai_giam_gia');
            $table->string('ma_giam_gia')->unique();
            $table->string('gia_tri'); 
            $table->datetime('ngay_bat_dau')->index();
            $table->datetime('ngay_het_han')->index();
            $table->integer('so_luong');
            $table->boolean('trang_thai');
            $table->datetime('ngay_tao')->index();
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('giam_gia');
    }
};