<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AttendanceTaken extends Event {

    use SerializesModels;

    public $attendance;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($attendance) {
        $this->attendance = $attendance;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn() {
        return [];
    }

}
