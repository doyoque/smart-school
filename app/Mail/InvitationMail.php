<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array $data
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {
            return $this->subject('School System Invitation')
                ->view('emails.invitation');
        } catch (\Exception $e) {
            Log::error(__FUNCTION__ . " InvitationMail Exception" . $e->getMessage(), $e->getTrace());
        }
    }
}
