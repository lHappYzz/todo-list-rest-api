# To-Do List REST API

This is a REST API built with Laravel that allows to manage a simple To-Do list. Includes an example of 
CRUD operations, Cache, Queues, Events usage. Also, there is an example of Google Calendar API integration 
(without OAuth authorization).

## Interesting directories

```
/
├── app/
    ├── Events/
        ├── TaskCreated.php
        ├── TaskDeleted.php
        └── TaskUpdated.php
    ├── Http/
        ├── Controllers/
            └──TaskController.php
        └── Resources/
            ├── TaskCollection.php
            └── TaskResource.php
    ├── Jobs/
        ├── CreateGoogleCalendarEventJob.php
        ├── DestroyGoogleCalendarEventJob.php
        ├── GoogleCalendarEventJob.php
        └── UpdateGoogleCalendarEventJob.php
    ├── Listeners/
        └── TaskEventSubscriber.php
    ├── Policies/
        └── TaskPolicy.php
    └── Services/
        └── GoogleCalendarService.php
└── docs/
    └── api/
        └── v1.0.yaml
```

## Technologies

- Laravel 12
- PHP 8.2
- MySQL 8.2
- Composer 2.7

## API documentation

The API documentation is available in the file [api-v1.0.yaml](./docs/api/v1.0.yaml).

