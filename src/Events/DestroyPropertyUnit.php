<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPropertyUnit
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $propertyUnit;

    public function __construct($propertyUnit)
    {
        $this->propertyUnit = $propertyUnit;
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
