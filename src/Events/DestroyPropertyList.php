<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPropertyList
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $propertylist;

    public function __construct($propertylist)
    {
        $this->propertylist = $propertylist;
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
