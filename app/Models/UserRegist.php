<?php

// app/Models/UserRegist.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRegist extends Model
{
    protected $table = 'user_regist';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email',
        'nama',
        'nama_usaha',
        'jenis_usaha',
        'profesi_pekerjaan',
        'alamat',
        'no_hp',
        'instagram',
        'facebook',
        'website',
        'alasan',
        'password',
        'foto',
        'status',
        'email_verified_at'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'status' => 'integer',
        'email_verified_at' => 'datetime'
    ];
}
