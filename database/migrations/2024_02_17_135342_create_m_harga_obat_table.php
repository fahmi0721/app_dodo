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
        Schema::create('m_harga_obat', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_obat")->index();
            $table->integer("harga");
            $table->date("valid_from");
            $table->date("valid_to");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_harga_obat');
    }
};
