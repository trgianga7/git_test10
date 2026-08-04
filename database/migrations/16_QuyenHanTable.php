<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quyen_han', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_chuc_vu')->constrained('chuc_vu')->cascadeOnDelete();
            $table->foreignId('id_chuc_nang')->constrained('chuc_nang')->cascadeOnDelete();
            $table->boolean('trang_thai')->default(1);
            //$table->date('ngay_tao')->nullable();
        
            $table->unique(['id_chuc_vu', 'id_chuc_nang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quyen_han');
    }
};