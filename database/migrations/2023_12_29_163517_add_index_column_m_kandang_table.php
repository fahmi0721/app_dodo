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
        Schema::table('m_kandang', function (Blueprint $table) {
            $table->index("id_peternak");
            $table->index("id_wilayah_penugasan");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_kandang', function (Blueprint $table) {
            //
        });
    }
};
