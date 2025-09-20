<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;
    protected $table = 'master_coa';
    protected $guarded = ['id'];

    public function neracaAwal()
    {
        return $this->hasMany(NeracaAwal::class, 'id_coa', 'id');
    }

    public function penerimaanDebet()
    {
        return $this->hasMany(Penerimaan::class, 'id_coa_debet', 'id');
    }

    public function penerimaanKredit()
    {
        return $this->hasMany(Penerimaan::class, 'id_coa_kredit', 'id');
    }

    public function pengeluaranDebet()
    {
        return $this->hasMany(Pengeluaran::class, 'id_coa_debet', 'id');
    }

    public function pengeluaranKredit()
    {
        return $this->hasMany(Pengeluaran::class, 'id_coa_kredit', 'id');
    }

    public function jurnalUmumDebet()
    {
        return $this->hasMany(JurnalUmum::class, 'id_coa_debet', 'id');
    }

    public function jurnalUmumKredit()
    {
        return $this->hasMany(JurnalUmum::class, 'id_coa_kredit', 'id');
    }
}
