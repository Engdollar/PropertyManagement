<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePropertyUnit
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $propertyUnit;

    public function __construct($request ,$propertyUnit)
    {
        $this->request = $request;
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
