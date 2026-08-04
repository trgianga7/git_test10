<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khach_hang', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('ten_khach_hang')->index();
            $table->boolean('loai_khach_hang');
            $table->string('anh_dai_dien')->nullable();
            $table->string('sdt')->unique();
            $table->string('mat_khau');
            $table->string('loai_tai_khoan')->default(0);
            $table->boolean('trang_thai')->default(0);
            $table->integer('so_lan_sai')->default(0);
            $table->timestamp('thoi_gian_khoa')->nullable();
            $table->decimal('vi', 20, 2)->default(0);
            $table->integer('diem')->default(0);
            $table->string('sdt_moi')->nullable()->index();
            $table->date('ngay_tao');
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khach_hang');
    }
};