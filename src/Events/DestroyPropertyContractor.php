<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPropertyContractor
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $property_contractor;

    public function __construct($property_contractor)
    {
        $this->property_contractor = $property_contractor;
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
