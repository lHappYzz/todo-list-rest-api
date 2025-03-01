<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

abstract class GoogleCalendarEventJob implements ShouldQueue
{
    use Queueable;

    /**
     * @param Task $task
     * @param string $logChannel
     */
    public function __construct(
        protected Task $task,
        protected string $logChannel = 'googleCalendar',
    ) {
        $this->queue = 'google-calendar';
    }
}
