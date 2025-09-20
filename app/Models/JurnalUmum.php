<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    use HasFactory;
    protected $table = 'jurnal_umum';
    protected $guarded = ['id'];
    public function coaDebet()
    {
        return $this->belongsTo(MasterCoa::class, 'id_coa_debet', 'id');
    }

    public function coaKredit()
    {
        return $this->belongsTo(MasterCoa::class, 'id_coa_kredit', 'id');
    }
}
