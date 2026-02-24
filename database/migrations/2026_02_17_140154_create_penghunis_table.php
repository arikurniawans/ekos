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
        Schema::create('penghuni', function (Blueprint $table) {
            $table->id('id_penghuni');
            $table->string('nama_penghuni', 100);
            $table->string('no_ktp', 30);
            $table->string('file_ktp', 255)->nullable();
            $table->string('no_hp', 20);
            $table->unsignedBigInteger('id_kamar');
            $table->date('tanggal_masuk');

            $table->foreign('id_kamar')
                  ->references('id_kamar')
                  ->on('kamar')
                  ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penghunis');
    }
};
