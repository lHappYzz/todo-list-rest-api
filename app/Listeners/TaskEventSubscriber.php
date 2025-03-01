<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Jobs\CreateGoogleCalendarEventJob;
use App\Jobs\DestroyGoogleCalendarEventJob;
use App\Jobs\UpdateGoogleCalendarEventJob;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;

class TaskEventSubscriber
{
    /**
     * @param TaskDeleted $event
     * @return void
     */
    public function handleTaskDeleted(TaskDeleted $event): void
    {
        if ($event->task->google_id) {
            DestroyGoogleCalendarEventJob::dispatch($event->task, 'tasks_index_' . Auth::id());
        }
    }

    /**
     * @param TaskUpdated $event
     * @return void
     */
    public function handleTaskUpdated(TaskUpdated $event): void
    {
        if ($event->task->google_id) {
            UpdateGoogleCalendarEventJob::dispatch($event->task);
        }
    }

    /**
     * @param TaskCreated $event
     * @return void
     */
    public function handleTaskCreated(TaskCreated $event): void
    {
        if ($event->task->due_date) {
            CreateGoogleCalendarEventJob::dispatch($event->task);
        }
    }

    /**
     * @param Dispatcher $events
     * @return array
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            TaskDeleted::class => 'handleTaskDeleted',
            TaskUpdated::class => 'handleTaskUpdated',
            TaskCreated::class => 'handleTaskCreated',
        ];
    }
}
