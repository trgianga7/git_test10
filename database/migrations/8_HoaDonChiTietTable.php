<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoa_don_chi_tiet', function (Blueprint $table) {
            $table->id();
            //$table->integer('id_hoa_don')->default(NULL);
            $table->foreignId('id_hoa_don')->constrained('hoa_don')->cascadeOnDelete();
            $table->string('id_san_pham_chi_tiet')->index();
            $table->string('ten_san_pham')->index();
            $table->integer('gia_ban');
            $table->integer('giam_gia_sp')->default(0);
            $table->integer('so_luong');
            $table->integer('tong_tien_hd');
            $table->date('ngay_tao');
            
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoa_don_chi_tiet');
    }
};