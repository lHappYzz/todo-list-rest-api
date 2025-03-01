<?php

namespace App\Models;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    /** @var string[] */
    protected $fillable = ['title', 'user_id', 'description', 'due_date'];

    /** @var class-string[] */
    protected $dispatchesEvents = [
        'deleting' => TaskDeleted::class,
        'updated' => TaskUpdated::class,
        'created' => TaskCreated::class,
    ];
}
