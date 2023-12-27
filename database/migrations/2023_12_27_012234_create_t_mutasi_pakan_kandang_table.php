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
        Schema::create('t_mutasi_pakan_kandang', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_proyek")->index();
            $table->bigInteger("id_pakan")->index();
            $table->bigInteger("id_transaksi")->index();
            $table->integer("jumlah");
            $table->date("tanggal");
            $table->enum("status",array("masuk","keluar"));
            $table->enum("via", array("pakan_masuk","transfer_masuk","tranfer_keluar","tepakai"));
            $table->string("keterangan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mutasi_pakan_kandang');
    }
};
