<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpSendNotification extends Notification{
    
    use Queueable;

    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    public $otp;

    public function __construct(string $subject, string $message){
        $this->message = $message;
        $this->subject = $subject;
        $this->fromEmail = env("MAIL_FROM_ADDRESS");
        $this->mailer = env("MAIL_MAILER");
        $this->otp = new Otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage{
        $otp = $this->otp->generate($notifiable->email, 'numeric', 6, 10);
        return (new MailMessage)
            ->mailer($this->mailer)
            ->subject($this->subject)
            ->greeting("Hello ".$notifiable->first_name)
            ->line($this->message)
            ->line('OTP: '. $otp->token)
            ->line('This OTP is valid for 10 minutes.')
            ->line('Don\'t show this OTP with anyone!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array{
        return [
            //
        ];
    }
}