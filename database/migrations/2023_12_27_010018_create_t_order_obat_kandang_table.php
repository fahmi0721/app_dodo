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
        Schema::create('t_order_obat_kandang', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_obat")->index();
            $table->bigInteger("id_proyek")->index();
            $table->integer("jumlah");
            $table->date("tanggal");
            $table->enum("status",array("inprogres","success","invalid"))->default("inprogres");
            $table->string("keterangan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_order_obat_kandang');
    }
};
