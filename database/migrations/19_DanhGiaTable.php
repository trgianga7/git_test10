<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('danh_gia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_san_pham_chi_tiet')->constrained('san_pham_chi_tiet')->cascadeOnDelete();

            $table->foreignId('id_hoa_don_chi_tiet')->constrained('hoa_don_chi_tiet')->cascadeOnDelete();

            $table->string('ma_danh_gia')->unique();

            $table->decimal('danh_gia',2,1);

            $table->text('noi_dung')->nullable();

            $table->string('id_khach_hang')->index();

            $table->string('trang_thai');

            $table->dateTime('thoi_gian_danh_gia');

            $table->index(
                ['id_san_pham_chi_tiet', 'trang_thai', 'thoi_gian_danh_gia'],
                'query_danh_gia'
            );
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('danh_gia');
    }
};