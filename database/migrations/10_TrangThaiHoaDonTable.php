<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trang_thai_hoa_don', function (Blueprint $table) {
            $table->id();
            $table->string('trang_thai');
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trang_thai_hoa_don');
    }
};