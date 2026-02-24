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
        Schema::table('penghuni', function (Blueprint $table) {

            $table->enum('kategori_kos', ['Bulanan','Tahunan'])
                  ->default('Bulanan')
                  ->after('tanggal_masuk');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penghuni', function (Blueprint $table) {

            $table->dropColumn('kategori_kos');

        });
    }
};
