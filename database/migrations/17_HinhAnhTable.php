<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hinh_anh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_san_pham_chi_tiet')->constrained('san_pham_chi_tiet')->cascadeOnDelete();
            $table->string('anh');
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hinh_anh');
    }
};