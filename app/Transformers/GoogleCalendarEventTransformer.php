<?php

namespace App\Transformers;

use App\Models\Task;

/**
 * Responsible for transforming local model fields into a Google Calendar event resource.
 */
class GoogleCalendarEventTransformer
{
    public static function transform(Task $task): array
    {
        return [
            'summary' => $task->title,
            'description' => $task->description,

            'start' => [
                'dateTime' => date('c', now()->timestamp),
                'timeZone' => 'UTC'
            ],
            'end' => [
                'dateTime' => date('c', strtotime($task->due_date)),
                'timeZone' => 'UTC'
            ],
        ];
    }
}
