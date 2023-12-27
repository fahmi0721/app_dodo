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
        Schema::create('t_pakan_trasnfer_gudang', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_proyek")->index();
            $table->bigInteger("id_pakan")->index();
            $table->integer("jumlah");
            $table->date("tanggal");
            $table->enum("status",array("masuk","keluar"));
            $table->string("keterangan")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pakan_trasnfer_gudang');
    }
};
