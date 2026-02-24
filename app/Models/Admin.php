<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';

    protected $primaryKey = 'id_admin';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'nama_admin'
    ];

    protected $hidden = [
        'password',
    ];
}
