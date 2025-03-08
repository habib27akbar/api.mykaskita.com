<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUnitKerja extends Model
{
    use HasFactory;
    protected $table = 'user_unit_kerja';
    protected $guarded = ['id'];

    public function unitKerjaDetail()
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit_kerja');
    }
}
