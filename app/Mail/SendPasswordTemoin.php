<?php

namespace App\Mail;

use App\Models\Inscription;
use App\Models\Temoin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordTemoin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private Inscription $user;
    private Temoin $temoin;
    private string $password;
    public function __construct($user,$temoin,$password)
    {
        $this->user=$user;
        $this->temoin=$temoin;
        $this->password=$password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.passwordTemoin',[
            "user"=>$this->user,
            "temoin"=>$this->temoin,
            "password"=>$this->password
        ]);
    }
}
