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
        Schema::create('m_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string("nama");
            $table->string("no_telp",20);
            $table->string("alamat");
            $table->enum("jenis_kelamin",array("pria","wanita"))->default("pria");
            $table->bigInteger("id_wilayah_penugasan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_karyawan');
    }
};
