<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class VerifyNotification extends VerifyEmail
{
    protected function buildMailMessage($url)
    {
        $mailMessage = new MailMessage();
        $mailMessage->markdown('emails.verify-notification');

        return $mailMessage
                ->subject('VX Project Field - Verifica tu correo electrónico')
                ->line(Lang::get('Please click the button below to verify your email address.'))
                ->action(Lang::get('Verify Email Address'), $url)
                ->line(Lang::get('If you did not create an account, no further action is required.'));
    }
}
