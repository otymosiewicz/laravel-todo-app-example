<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->where('email', 'user@example.com')->first();

        Task::factory()
            ->forUser($user)
            ->withDoneStatus()
            ->create();

        Task::factory()
            ->forUser($user)
            ->withTodoStatus((Carbon::now())->modify('+1 day +1 minute'))
            ->create();

        Task::factory()
            ->forUser($user)
            ->withInProgressStatus((Carbon::now())->modify('+1 day +2 minutes'))
            ->create();
    }
}
