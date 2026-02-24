<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $primaryKey = 'id_pembayaran';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_penghuni',
        'bulan',
        'jumlah_bayar',
        'tanggal_bayar',
        'status_pembayaran',
        'bukti_bayar'
    ];

    // Relasi ke Penghuni
    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni', 'id_penghuni');
    }
}
