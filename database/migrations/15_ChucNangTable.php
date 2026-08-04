<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chuc_nang', function (Blueprint $table) {
            $table->id();
            $table->string('ten_chuc_nang');
            $table->string('route')->unique();
            $table->boolean('trang_thai')->default(1);
            $table->date('ngay_tao');
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chuc_nang');
    }
};