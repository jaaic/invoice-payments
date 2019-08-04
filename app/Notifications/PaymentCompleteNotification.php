<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PaymentCompleteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'nexmo', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment complete for account: ' . $this->invoice->account_nr)
            ->line('Payment amount=' . $this->invoice->amount . $this->invoice->currency);
    }

    /**
     * Get the Nexmo / SMS representation of the notification.
     *
     * @param mixed $notifiable
     * @return NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())
            ->content('Payment complete for account: ' . $this->invoice->account_nr . '. Payment amount=' . $this->invoice->amount . $this->invoice->currency);
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Payment complete for account: ' . $this->invoice->account_nr,
            'content' => 'Payment amount=' . $this->invoice->amount . $this->invoice->currency,
        ]);
    }
}
