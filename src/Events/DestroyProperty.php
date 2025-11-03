<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyProperty
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $property;

    public function __construct($property)
    {
        $this->property = $property;
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
