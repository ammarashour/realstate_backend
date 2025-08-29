<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;
use App\Mail\TestMail;
use Exception;

class EmailController extends Controller
{
    public function index()
    {
        $testMailData = [
            'title' => 'Test Email From prozeed.com',
            'body' => 'hello this is test mail'
        ];

        $test = Mail::to('hello@prozeed.email');
        // $test->SMTPOptions = array(
        //     'ssl' => array(
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //         'allow_self_signed' => false
        //     )
    // );
        // dd($test);


        $test->send(new SendMail($testMailData));
        // $mail_to_user = 'hello@prozeed.email';

        // try {
        //     Mail::send('emails.testMail2', [], function($msg) use ($mail_to_user) {
        //         $msg->to($mail_to_user);
        //         $msg->subject("Document Shared");
        //     });
        // } catch (Exception $e) {

        //     dd($e);
        // }



        dd('Success! Email has been sent successfully.');
    }
}
