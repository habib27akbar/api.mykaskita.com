<?php
// app/Mail/VerifyEmail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $password;
    public string $nama;

    public function __construct(string $password, string $nama)
    {
        $this->password = $password;
        $this->nama = $nama;
    }

    public function build()
    {
        return $this->subject('Lupa Password Kaskita')
            ->view('emails.password'); // resources/views/emails/verify.blade.php
    }
}
