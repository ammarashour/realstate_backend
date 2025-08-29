<?php

namespace Modules\Core\Emails;

use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailRegistedUserWithoutVerification extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
        $backendSettingService = app()->make(BackendSettingServiceInterface::class);
        $backendSetting = $backendSettingService->get();
        $this->details['from_name'] = $backendSetting->sender_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->details['title'])
                    ->view('email.register_success')
                    ->with('details', $this->details);
    }
}
