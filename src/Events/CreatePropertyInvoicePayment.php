<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class CreatePropertyInvoicePayment
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $type;
    public $invoicePayment;

    public function __construct($request, $type, $invoicePayment)
    {
        $this->request = $request;
        $this->type = $type;
        $this->invoicePayment = $invoicePayment;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
