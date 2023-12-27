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
        Schema::create('t_recording', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_proyek")->index();
            $table->bigInteger("id_pakan")->index();
            $table->integer("pakan_pakai")->default(0);
            $table->integer("pakan_sisa");
            $table->integer("jumlah_ayam_mati")->default(0);
            $table->integer("jumlah_ayam_panen")->default(0);
            $table->string("catatan_intake")->nullable();
            $table->string("catatan_bw")->nullable();
            $table->string("catatan_fcr")->nullable();
            $table->date("tanggal");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_recording');
    }
};
