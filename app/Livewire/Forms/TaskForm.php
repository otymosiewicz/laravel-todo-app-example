<?php

namespace App\Livewire\Forms;

use App\Enum\Priority;
use App\Enum\Status;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TaskForm extends Form
{
    #[Validate('required|min:3|max:255')]
    public ?string $title = null;

    #[Validate('nullable|string')]
    public ?string $description = null;

    #[Validate('nullable|date')]
    public ?string $deadline = null;

    #[Validate('required|in:to-do,in-progress,done')]
    public Status $status = Status::TODO;

    #[Validate('required|in:low,medium,high')]
    public Priority $priority = Priority::LOW;
}
