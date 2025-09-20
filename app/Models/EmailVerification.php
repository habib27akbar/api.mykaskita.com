<?php
// app/Models/EmailVerification.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    protected $table = 'email_verifications';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['email', 'token', 'expires_at'];
    protected $casts = ['expires_at' => 'datetime'];
}
