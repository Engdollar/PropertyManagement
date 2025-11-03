<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPropertyInspection
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $property_inspection;

    public function __construct($property_inspection)
    {
        $this->property_inspection = $property_inspection;
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
