<?php

namespace Workdo\PropertyManagement\Events;

use Illuminate\Queue\SerializesModels;

class DestroyExpenseTracking
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $expense_tracking;

    public function __construct($expense_tracking)
    {
        $this->expense_tracking = $expense_tracking;
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
