<?php

return [
    'google_calendar_id' => env('GOOGLE_CALENDAR_ID'),
    'base_url' => "https://www.googleapis.com/calendar/v3/calendars/",
    'google_service_account_storage_path' => storage_path(env('GOOGLE_SERVICE_ACCOUNT_STORAGE_PATH')),
    'token_ttl' => env(key: 'GOOGLE_ACCESS_TOKEN_TTL', default: 1800),
];
