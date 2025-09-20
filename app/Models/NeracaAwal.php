<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeracaAwal extends Model
{
    use HasFactory;
    protected $table = 'jurnal_umum';
    protected $guarded = ['id'];
}
