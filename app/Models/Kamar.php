<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar'; // nama tabel

    protected $primaryKey = 'id_kamar'; // primary key custom

    public $incrementing = true; // karena bigint auto increment

    protected $keyType = 'int';

    public $timestamps = false;
    // set true jika tabel Anda punya created_at & updated_at

    protected $fillable = [
        'nomor_kamar',
        'kapasitas',
        'tarif',
        'status'
    ];
}
