<?php

namespace App\Mail;

use App\Models\Enseignant;
use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordProf extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private Inscription $user;
    private Enseignant $enseignant;
    private string $password;

    public function __construct($user,$enseignant,$password)
    {
        $this->user=$user;
        $this->enseignant=$enseignant;
        $this->password=$password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.passwordprof',[
        "user"=>$this->user,
        "enseignant"=>$this->enseignant,
        "password"=>$this->password]);
    }
}
