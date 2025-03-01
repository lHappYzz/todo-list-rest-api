<?php

namespace App\Jobs;

use App\Exceptions\GoogleCalendarServiceException;
use App\Models\Task;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Log;

class UpdateGoogleCalendarEventJob extends GoogleCalendarEventJob
{
    /**
     * Create a new job instance.
     */
    public function __construct(
        Task $task,
    ) {
        parent::__construct($task);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->task->google_id === null) {
            $this->fail('Trying to update task not linked to Google Calendar.');
        }

        try {
            /** @var GoogleCalendarService $google */
            $google = app(GoogleCalendarService::class);

            $response = $google->updateEvent($this->task);

            Log::channel($this->logChannel)->info(
                'Google calendar event updated. ' . print_r($response, true)
            );
        } catch (GoogleCalendarServiceException $e) {
            Log::channel($this->logChannel)->error($e);
        }
    }
}
