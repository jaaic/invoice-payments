<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class InvoicePayment
{
    use Dispatchable, SerializesModels;

    public $invoice;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }
}
