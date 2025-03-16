<?php

namespace App\Jobs;

use App\Mail\TaskDeadlineReminderMail;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendReminderEmailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Task $task)
    {
        $this->delay(
            (clone $this->task->deadline)
                ->subDay()
        );

        $this->queue = 'task_deadline_reminder';
    }

    public function middleware(): array
    {
        return [
            Skip::when(
                $this->isNewerReminderAlreadyQueued()
            ),
            Skip::when(
                $this->isExpired()
            ),
        ];
    }

    private function isNewerReminderAlreadyQueued(): bool
    {
        return DB::table('jobs')
            ->where('queue', '=', $this->queue)
            ->whereLike('payload', '%'.$this->task->id.'%')
            ->count() >= 2;
    }

    private function isExpired(): bool
    {
        return $this->delay->isBefore(
            Carbon::yesterday()
                ->setTimeFromTimeString($this->delay->toTimeString())
        );
    }

    public function handle(): void
    {
        Mail::send(
            new TaskDeadlineReminderMail(
                $this->task
            )
        );
    }
}
