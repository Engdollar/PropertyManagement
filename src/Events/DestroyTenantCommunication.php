<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTenantCommunication
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $tenant_communication;

    public function __construct($tenant_communication)
    {
        $this->tenant_communication = $tenant_communication;
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
