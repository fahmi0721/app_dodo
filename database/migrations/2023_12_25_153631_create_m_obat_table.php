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
        Schema::create('m_obat', function (Blueprint $table) {
            $table->id();
            $table->string("nama")->unique();
            $table->string("kemasan");
            $table->string("id_satuan");
            $table->integer("harga")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_obat');
    }
};
