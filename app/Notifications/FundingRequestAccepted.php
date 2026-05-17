<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FundingRequestAccepted extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Your funding request was accepted')
                    ->line('Good news — an investor accepted your funding request. You can now open the conversation in the platform.')
                    ->action('Open conversation', url('/'))
                    ->line('Thank you for using our platform!');
    }
}
