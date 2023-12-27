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
        Schema::create('t_obat_kandang', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_obat")->index();
            $table->bigInteger("id_proyek")->index();
            $table->bigInteger("id_order")->index();
            $table->integer("jumlah");
            $table->date("tanggal");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_obat_kandang');
    }
};
