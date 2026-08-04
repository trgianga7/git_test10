<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('san_pham', function (Blueprint $table) {
            $table->id();
            $table->integer('id_danh_muc');
            $table->uuid('key_sp')->unique();
            $table->string('ten_san_pham')->index();
            $table->boolean('trang_thai')->default(0);
            $table->string('nguoi_tao')->default('Không có')->index();
            $table->date('ngay_tao');
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('san_pham');
    }
};