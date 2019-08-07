<?php

namespace App\Listeners;

use App\Events\InvoicePayment;
use App\Notifications\PaymentCompleteNotification;
use Illuminate\Support\Facades\Notification;

class SendInvoicePaymentNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InvoicePayment  $event
     * @return void
     */
    public function handle(InvoicePayment $event)
    {
        Notification::route('mail', $event->invoice->owner_email)
            ->notify(new PaymentCompleteNotification($event->invoice));

        Notification::route('sms', $event->invoice->owner_phone_number)
            ->notify(new PaymentCompleteNotification($event->invoice));

        Notification::route('broadcast', $event->invoice->owner_phone_number)
            ->notify(new PaymentCompleteNotification($event->invoice));
    }
}
