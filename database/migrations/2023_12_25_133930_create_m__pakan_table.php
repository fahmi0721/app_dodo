<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_pakan', function (Blueprint $table) {
            $table->id();
            $table->string("nama",100)->unique();
            $table->string("keterangan");
            $table->bigInteger("id_merk");
            $table->bigInteger("id_tipe");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_pakan');
    }
};
