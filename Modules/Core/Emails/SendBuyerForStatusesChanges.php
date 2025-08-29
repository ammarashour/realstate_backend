<?php

namespace Modules\Core\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBuyerForStatusesChanges extends Mailable
{
    use Queueable, SerializesModels;

    protected $data, $mail;
    public function __construct($data, $mail)
    {
        $this->data = $data;
        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->mail['subject'])
                ->from("buyser@gmail.com", $this->mail['from_name'])
                ->view('email.send_buyer_for_statuses_changes')
                ->with(['data' => $this->data, 'mail' => $this->mail]);
    }
}
