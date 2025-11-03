<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPropertyInvoice
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $propertyInvoice;

    public function __construct($propertyInvoice)
    {
        $this->propertyInvoice = $propertyInvoice;
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
