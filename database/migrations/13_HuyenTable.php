<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('huyen', function (Blueprint $table) {
            $table->id();
            $table->integer('district_id')->unique();
            $table->string('district_name');
            $table->integer('province_id');
            //$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('huyen');
    }
};