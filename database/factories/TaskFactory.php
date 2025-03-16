<?php

namespace Database\Factories;

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
        $status = fake()->randomElement(['to-do', 'in-progress', 'done']);
        $deadline = $status === 'done' ? fake()->dateTimeBetween(startDate: '-1 month') : fake()->dateTimeBetween(startDate: 'now', endDate: '+1 month');

        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => $status,
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'deadline' => $deadline,
        ];
    }

    public function withDoneStatus(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'done',
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
            'status' => 'in-progress',
            'deadline' => $deadline ?? $this->getFutureDateTime(),
        ]);
    }

    public function withTodoStatus(?DateTimeInterface $deadline = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'to-do',
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
