<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoa_don', function (Blueprint $table) {
            $table->id();
            $table->integer('id_khach_hang')->nullable()->index();
            $table->string('ma_hd')->unique();
            $table->string('dia_chi_hd');
            $table->string('ten_nguoi_nhan')->default('Không có')->index();
            $table->string('sdt_nguoi_nhan')->default('Không có')->index();
            $table->integer('tong_tien_goc');
            $table->string('ten_giam_gia')->nullable();
            $table->integer('giam_gia');
            $table->string('loai_giam_gia_hd')->nullable();
            $table->integer('tong_tien_thuc');
            $table->integer('loai_hinh');
            $table->integer('trang_thai_thanh_toan');
            $table->string('trang_thai');
            $table->datetime('ngay_tao')->index();
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoa_don');
    }
};