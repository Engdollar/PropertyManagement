<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class UpdateTenantCommunication
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $tenant_communication;

    public function __construct($request ,$tenant_communication)
    {
        $this->request = $request;
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
