<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTenantRequest
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $tenant_request;

    public function __construct($tenant_request)
    {
        $this->tenant_request = $tenant_request;
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
