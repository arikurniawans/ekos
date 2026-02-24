<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Penghuni extends Model
{
    protected $table = 'penghuni';

    protected $primaryKey = 'id_penghuni';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'nama_penghuni',
        'no_ktp',
        'file_ktp',
        'no_hp',
        'id_kamar',
        'tanggal_masuk',
        'kategori_kos'
    ];

    // Relasi ke Kamar
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }

    public function pembayaranTerakhir()
    {
        return $this->hasOne(Pembayaran::class, 'id_penghuni', 'id_penghuni')
                    ->latest('tanggal_bayar');
    }

    public function getStatusPembayaranAttribute()
    {
        $today        = Carbon::today();
        $tanggalMasuk = Carbon::parse($this->tanggal_masuk);

        // Ambil pembayaran terakhir yang sudah ada bukti bayar
        $pembayaranTerakhir = $this->pembayaran()
            ->whereNotNull('bukti_bayar')
            ->orderByDesc('tanggal_bayar')
            ->first();

        // Tentukan baseline:
        // - Jika sudah pernah bayar → pakai tanggal_bayar terakhir
        // - Jika belum pernah bayar → pakai tanggal_masuk
        $baseline = $pembayaranTerakhir
            ? Carbon::parse($pembayaranTerakhir->tanggal_bayar)
            : $tanggalMasuk;

        // Hitung jatuh tempo dari baseline
        if ($this->kategori_kos == 'Bulanan') {
            $jatuhTempo = $baseline->copy()->addDays(30);
        } elseif ($this->kategori_kos == 'Tahunan') {
            $jatuhTempo = $baseline->copy()->addDays(360);
        } else {
            $jatuhTempo = $baseline->copy()->addDays(30); // default
        }

        // Hitung selisih hari (positif = belum jatuh tempo, negatif = sudah lewat)
        $selisih = $today->diffInDays($jatuhTempo, false);

        // ========================
        // STATUS: SUDAH LEWAT JATUH TEMPO
        // ========================
        if ($selisih < 0) {
            return [
                'label' => 'Telat Bayar ' . abs((int) $selisih) . ' Hari',
                'class' => 'danger'
            ];
        }

        // ========================
        // STATUS: TEPAT JATUH TEMPO HARI INI
        // ========================
        if ($selisih == 0) {
            return [
                'label' => 'Jatuh Tempo',
                'class' => 'warning'
            ];
        }

        // ========================
        // STATUS: MENDEKATI JATUH TEMPO (H-7 atau kurang)
        // ========================
        if ($selisih <= 7) {
            return [
                'label' => 'Jatuh Tempo ' . (int) $selisih . ' Hari Lagi',
                'class' => 'warning'
            ];
        }

        // ========================
        // STATUS: MASIH JAUH DARI JATUH TEMPO
        // Bedakan label antara yang sudah bayar dan belum
        // ========================
        if ($pembayaranTerakhir) {
            return [
                'label' => 'Lunas / Belum Jatuh Tempo',
                'class' => 'success'
            ];
        }

        return [
            'label' => 'Belum Jatuh Tempo',
            'class' => 'success'
        ];
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_penghuni', 'id_penghuni');
    }

}
