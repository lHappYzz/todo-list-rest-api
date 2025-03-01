<?php

namespace App\Jobs;

use App\Exceptions\GoogleCalendarServiceException;
use App\Models\Task;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DestroyGoogleCalendarEventJob extends GoogleCalendarEventJob
{
    /**
     * Create a new job instance.
     */
    public function __construct(
        Task $task,
        protected string $forgetCacheKeyOnSuccess,
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

            $google->destroyEvent($this->task->google_id);
        } catch (GoogleCalendarServiceException $e) {
            Log::channel($this->logChannel)->error($e);
        }

        $this->task->forceDeleteQuietly();

        Log::channel($this->logChannel)->info(
            'Google calendar event destroyed. Id: ' . $this->task->id . '. Google Id: ' . $this->task->google_id
        );

        Cache::forget($this->forgetCacheKeyOnSuccess);
    }
}
