<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dinh_kem_danh_gia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_danh_gia')->constrained('danh_gia')->cascadeOnDelete();
            $table->string('dinh_kem');
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dinh_kem_danh_gia');
    }
};