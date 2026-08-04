<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tinh', function (Blueprint $table) {
            $table->id();
            $table->integer('province_id')->unique();
            $table->string('province_name');
            //$table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('tinh');
    }
};