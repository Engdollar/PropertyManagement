<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTenant
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $tenant;

    public function __construct($tenant)
    {
        $this->tenant = $tenant;
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
