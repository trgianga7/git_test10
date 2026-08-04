<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('id_chuc_vu')->index();
            $table->string('ten_nguoi_dung')->index();
            $table->string('anh_dai_dien')->nullable();
            $table->string('email')->unique();
            $table->string('mat_khau');
            $table->boolean('trang_thai')->default(0);
            $table->integer('so_lan_sai')->default(0);
            $table->timestamp('thoi_gian_khoa')->nullable();
            $table->integer('diem')->default(0);
            $table->string('sdt_lien_he')->nullable()->index();
            $table->date('ngay_tao')->index();
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguoi_dung');
    }
};