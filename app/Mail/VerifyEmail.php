<?php
// app/Mail/VerifyEmail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $link;
    public string $nama;

    public function __construct(string $link, string $nama)
    {
        $this->link = $link;
        $this->nama = $nama;
    }

    public function build()
    {
        return $this->subject('Verifikasi Email Akun Anda')
            ->view('emails.verify'); // resources/views/emails/verify.blade.php
    }
}
