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
        Schema::create('t_pakan_masuk_gudang', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("id_pakan")->index();
            $table->bigInteger("id_vendor")->index();
            $table->integer("jumlah");
            $table->date("tanggal");
            $table->string("keterangan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pakan_masuk_gudang');
    }
};
