<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordRequest extends Notification
{
    use Queueable;
<<<<<<< HEAD
    protected $token;
=======

>>>>>>> fdfd20c57abebeb9d2649198baee34df16ef0fa6
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
<<<<<<< HEAD
        $this->token =  $token;
=======
        $this->token = $token;
>>>>>>> fdfd20c57abebeb9d2649198baee34df16ef0fa6
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
<<<<<<< HEAD
        // $url = url('reset-password/?token=' . $this->token);
        return (new MailMessage)
                    ->subject('Reset Password Token Fakebook')
                    ->line('You are receiving this email because we received a password reset request for your account.')
                    ->line('-----------------------------------')
                    ->line($this->token)
                    ->line('-----------------------------------')
                    ->line('If you did not request a password reset, no further action is required.');
    }

    // /**
    //  * Get the array representation of the notification.
    //  *
    //  * @param  mixed  $notifiable
    //  * @return array
    //  */
    // public function toArray($notifiable)
    // {
    //     return [
    //         //
    //     ];
    // }
=======
        $url = url('reset-password/?token=' . $this->token);
        
        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url($url))
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
>>>>>>> fdfd20c57abebeb9d2649198baee34df16ef0fa6
}
