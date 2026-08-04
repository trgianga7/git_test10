<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('danh_muc', function (Blueprint $table) {
            $table->id();
            $table->string('ten_danh_muc');
            $table->boolean('trang_thai')->default(0);
            $table->date('ngay_tao');
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('danh_muc');
    }
};