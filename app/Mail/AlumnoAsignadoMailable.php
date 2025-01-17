<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlumnoAsignadoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $grupo;
    public $profesor;

    /**
     * Create a new message instance.
     *
     * @param $grupo
     * @param $profesor
     */
    public function __construct($grupo, $profesor)
    {
        $this->grupo = $grupo;
        $this->profesor = $profesor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Asignación a un nuevo grupo')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->view('emails.alumno_asignado');
    }
}
