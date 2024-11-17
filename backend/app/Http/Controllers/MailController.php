<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class MailController extends Controller
{
    public function sendTestEmail()
    {
        $subject = 'Test Email';
        $message = 'This is a test email sent from Laravel API without using Blade view.';

        // Sử dụng Mail::raw để gửi email thuần (plain text)
        Mail::raw($message, function ($message) use ($subject) {
            $message->to('lehanhhaidang@gmail.com')
                ->subject($subject);
        });

        return response()->json(['message' => 'Test email has been sent!']);
    }
}
