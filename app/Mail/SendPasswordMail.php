<?php

namespace App\Mail;

use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
private Inscription $user;
private Eleve $eleve;
private string $password;

    public function __construct($user,$eleve,$password)
    {
        $this->user=$user;
        $this->eleve=$eleve;
        $this->password=$password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.password-sent-mail',[
            "user"=>$this->user,
            "eleve"=>$this->eleve,
            "password"=>$this->password
        ]);
    }
}
