<?php

namespace App\Jobs;

use App\Exceptions\GoogleCalendarServiceException;
use App\Models\Task;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Log;

class CreateGoogleCalendarEventJob extends GoogleCalendarEventJob
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
        /** @var GoogleCalendarService $google */
        $google = app(GoogleCalendarService::class);

        try {
            $response = $google->addEvent(
                $this->task,
            );

            $this->task->google_id = $response['id'];
            $this->task->saveQuietly();

            Log::channel($this->logChannel)->info(
                'Google calendar event created. ' . print_r($response, true)
            );
        } catch (GoogleCalendarServiceException $e) {
            Log::channel($this->logChannel)->error($e);
        }
    }
}
