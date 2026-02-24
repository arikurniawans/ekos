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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_penghuni');
            $table->string('bulan', 20);
            $table->decimal('jumlah_bayar', 12, 2);
            $table->date('tanggal_bayar');
            $table->string('status_pembayaran', 20);

            $table->foreign('id_penghuni')
                  ->references('id_penghuni')
                  ->on('penghuni')
                  ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
