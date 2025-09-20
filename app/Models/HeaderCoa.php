<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderCoa extends Model
{
    use HasFactory;
    protected $table = 'header_coa';
    protected $guarded = ['id'];

    public function masterCoa()
    {
        return $this->hasMany(MasterCoa::class, 'id_header', 'id');
    }
}
