<?php

namespace Database\Factories;

use App\Enum\Priority;
use App\Enum\Status;
use App\Models\Task;
use App\Models\User;
use DateTime;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement([Status::TODO->value, Status::IN_PROGRESS->value, Status::DONE->value]);
        $deadline = $status === 'done' ? fake()->dateTimeBetween(startDate: '-1 month') : fake()->dateTimeBetween(startDate: 'now', endDate: '+1 month');

        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => $status,
            'priority' => fake()->randomElement([Priority::LOW->value, Priority::MID->value, Priority::HIGH->value]),
            'deadline' => $deadline,
        ];
    }

    public function withDoneStatus(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Status::DONE->value,
            'deadline' => $this->getPastDateTime(),
        ]);
    }

    private function getPastDateTime(): DateTime
    {
        return fake()->dateTimeBetween(startDate: '-1 month');
    }

    private function getFutureDateTime(): DateTime
    {
        return fake()->dateTimeBetween(startDate: 'now', endDate: '+1 month');
    }

    public function withInProgressStatus(?DateTimeInterface $deadline = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Status::IN_PROGRESS->value,
            'deadline' => $deadline ?? $this->getFutureDateTime(),
        ]);
    }

    public function withTodoStatus(?DateTimeInterface $deadline = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Status::TODO->value,
            'deadline' => $deadline ?? $this->getFutureDateTime(),
        ]);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
