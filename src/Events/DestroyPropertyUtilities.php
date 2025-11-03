<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPropertyUtilities
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $property_utility;

    public function __construct($property_utility)
    {
        $this->property_utility = $property_utility;
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
