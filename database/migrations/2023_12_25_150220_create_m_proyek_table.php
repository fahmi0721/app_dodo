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
        Schema::create('m_proyek', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_peternak");
            $table->bigInteger("id_kandang");
            $table->bigInteger("id_ppl");
            $table->integer("populasi");
            $table->date("tgl_chekin")->nullable();
            $table->string("bw_tiba")->nullable();
            $table->string("kode_box")->nullable();
            $table->string("plat_polisi")->nullable();
            $table->dateTime("waktu_berangkat")->nullable();
            $table->dateTime("waktu_tiba")->nullable();
            $table->string("no_spb")->nullable();
            $table->string("driver")->nullable();
            $table->string("jenis_doc")->nullable();
            $table->string("keterangan")->nullable();
            $table->enum("status", array('active','inactive'))->default("active");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_proyek');
    }
};
