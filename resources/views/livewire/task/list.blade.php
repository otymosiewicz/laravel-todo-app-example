<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public array $filters = [
        'status' => [],
        'priority' => [],
        'deadline' => [
            'from' => '',
            'to' => '',
        ],
    ];

    #[On('update-filters')]
    public function updateFilters(array $filters): void
    {
        $this->filters = $filters;
        $this->resetPage();
    }

    #[Computed]
    public function tasks()
    {
        return auth()
            ->user()
            ->tasks()
            ->when(
                !empty($this->filters['status']),
                fn($query) => $query->whereIn('status', array_keys(array_filter($this->filters['status']))),
            )
            ->when(
                !empty($this->filters['priority']),
                fn($query) => $query->whereIn('priority', array_keys(array_filter($this->filters['priority']))),
            )
            ->when(
                !empty($this->filters['deadline']['from']) && !empty($this->filters['deadline']['to']),
                fn($query) => $query->whereBetween('deadline', [
                    $this->filters['deadline']['from'],
                    $this->filters['deadline']['to'],
                ]),
            )
            ->when(
                !empty($this->filters['deadline']['from']) && empty($this->filters['deadline']['to']),
                fn($query) => $query->where('deadline', '>=', $this->filters['deadline']['from']),
            )
            ->when(
                empty($this->filters['deadline']['from']) && !empty($this->filters['deadline']['to']),
                fn($query) => $query->where('deadline', '<=', $this->filters['deadline']['to']),
            )
            ->paginate(5);
    }

}; ?>

<div class="p-6 text-gray-900">
    <div class="mb-5 flex gap-3">
        <button
            wire:click="$dispatchTo('task.create', 'show-modal')"
            type="button"
            class="focus:outline-hidden inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-2 py-1.5 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700"
        >
            <x-icon class="h-4 w-4" name="plus" />
            New task
        </button>

        <livewire:task.partials.filter_button />
    </div>

    <div class="mb-5 flex flex-col space-y-4">
        @if ($this->tasks->isEmpty())
            <span class="text-center text-gray-500">Nothing here yet, start by adding a task.</span>
        @endif

        @foreach ($this->tasks as $task)
            <livewire:task.partials.card :$task :key="$task->id" />
        @endforeach
    </div>

    <livewire:task.create />
    <livewire:task.update />

    <livewire:task.partials.change_history />

    {{ $this->tasks->links() }}
</div>
