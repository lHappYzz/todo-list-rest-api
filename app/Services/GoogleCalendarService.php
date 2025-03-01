<?php

namespace App\Services;

use App\Classes\Enum\HttpMethodEnum;
use App\Exceptions\GoogleCalendarServiceException;
use App\Models\Task;
use App\Transformers\GoogleCalendarEventTransformer;
use Google\Client;
use Google\Exception as GoogleException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoogleCalendarService
{
    /** @var string */
    protected string $calendarId;

    /** @var string */
    protected string $baseUrl;

    /** @var string */
    protected string $token;

    /** @var string */
    private const ACCESS_TOKEN_CACHE_KEY = 'google_access_token';

    /**
     * @throws GoogleCalendarServiceException
     */
    public function __construct(private readonly Client $client)
    {
        $this->calendarId = config('google-calendar.google_calendar_id');
        $this->baseUrl = config('google-calendar.base_url') . $this->calendarId . '/events';
        try {
            $this->setupClient();
        } catch (GoogleException $exception) {
            throw new GoogleCalendarServiceException($exception);
        }
    }

    /**
     * @param Task $task
     * @return array
     * @throws GoogleCalendarServiceException
     */
    public function addEvent(Task $task): array
    {
        return $this->makeRequest(
            $this->baseUrl,
            HttpMethodEnum::POST,
            GoogleCalendarEventTransformer::transform($task)
        );
    }

    /**
     * @param Task $task
     * @return array
     * @throws GoogleCalendarServiceException
     */
    public function updateEvent(Task $task): array
    {
        return $this->makeRequest(
            $this->baseUrl . "/$task->google_id",
            HttpMethodEnum::PUT,
            GoogleCalendarEventTransformer::transform($task)
        );
    }

    /**
     * @param string $eventId
     * @return void
     * @throws GoogleCalendarServiceException
     */
    public function destroyEvent(string $eventId): void
    {
        $this->makeRequest(
            $this->baseUrl . "/$eventId",
            HttpMethodEnum::DELETE
        );
    }

    /**
     * @param string $url
     * @param HttpMethodEnum $httpMethod
     * @param array $data
     * @return array|null
     * @throws GoogleCalendarServiceException
     */
    private function makeRequest(string $url, HttpMethodEnum $httpMethod, array $data = []): null | array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json'
            ])->{$httpMethod->value}($url, $data);
        } catch (ConnectionException $e) {
            throw new GoogleCalendarServiceException($e);
        }

        if (!$response->successful()) {
            throw new GoogleCalendarServiceException($response->body());
        }

        return $response->json();
    }

    /**
     * @throws GoogleException
     */
    private function setupClient(): void
    {
        $this->client->setAuthConfig(config: config(key: 'google-calendar.google_service_account_storage_path'));
        $this->client->addScope(scope_or_scopes: 'https://www.googleapis.com/auth/calendar');

        $this->token = Cache::remember(
            key: self::ACCESS_TOKEN_CACHE_KEY,
            ttl: config(key: 'google-calendar.token_ttl'),
            callback: fn() => $this->client->fetchAccessTokenWithAssertion()['access_token']
        );
    }
}
