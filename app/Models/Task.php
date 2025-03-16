<?php

namespace App\Models;

use App\Enum\Priority;
use App\Enum\Status;
use App\Jobs\SendReminderEmailJob;
use Database\Factories\TaskFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    use HasUuids;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id', 'title', 'description', 'priority', 'status', 'deadline',
    ];

    protected $guarded = [
        'hash'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'status' => Status::class,
            'priority' => Priority::class,
        ];
    }

    protected static function booted(): void
    {
        parent::booted();

        $dispatchReminder = function (Task $task) {
            if ($task->deadline instanceof DateTimeInterface && in_array($task->status,
                    [Status::TODO, Status::IN_PROGRESS])) {
                SendReminderEmailJob::dispatch($task);
            }
        };

        static::created($dispatchReminder);
        static::creating(function (Task $task) {
            do {
                $hash = substr(hash('sha256', Str::uuid()->toString()), 0, 24);
            } while (Task::query()->where('hash', $hash)->exists());

            $task->hash = $hash;
        });

        static::updated(function (Task $task) use ($dispatchReminder): void {
            if ($task->isDirty('deadline') || $task->isDirty('status')) {
                $dispatchReminder($task);
            }
        });
    }
}
