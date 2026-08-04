<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thoi_gian_trang_thai', function (Blueprint $table) {
            $table->id();
            $table->integer('id_hoa_don');
            $table->string('ls_trang_thai');
            $table->datetime('thoi_gian_trang_thai');
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thoi_gian_trang_thai');
    }
};