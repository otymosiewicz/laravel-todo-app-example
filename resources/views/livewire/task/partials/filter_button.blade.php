<?php

use App\Enum\Priority;
use App\Enum\Status;
use Livewire\Volt\Component;

new class extends Component {
    public bool $isVisible = false;
    public array $filters = [];

    public function mount(): void
    {
        $this->filters['status'] = collect(Status::cases())
            ->mapWithKeys(fn($item) => [$item->value => true])
            ->toArray();

        $this->filters['priority'] = collect(Priority::cases())
            ->mapWithKeys(fn($item) => [$item->value => true])
            ->toArray();
    }

    public function updated($property): void
    {
        if($property === 'filters.deadline.from' || $property === 'filters.deadline.to') {
            if(!empty($this->filters['deadline']['from']) && !empty($this->filters['deadline']['to'])) {
                $this->validate([
                    'filters.deadline.to' => 'after_or_equal:filters.deadline.from',
                    'filters.deadline.from' => 'before_or_equal:filters.deadline.to',
                ]);
                return;
            }
            $this->validate();
        }
    }

    public function updateList(): void
    {
        if (empty($this->getErrorBag()->first())) {
            $this->dispatch('update-filters', $this->filters)->to('task.list');
        }
    }

    protected function rules(): array
    {
        return [
            'filters.deadline.from' => ['nullable', 'date'],
            'filters.deadline.to' => ['nullable', 'date'],
        ];
    }

    protected function messages(): array
    {
        return [
            'filters.deadline.from.before_or_equal' => 'The "from" date cannot be later than the "to" date.',
            'filters.deadline.to.after_or_equal' => 'The "to" date cannot be earlier than the "from" date.'
        ];

    }
}; ?>

<div class="relative">
    <button
        wire:click="$toggle('isVisible')"
        type="button"
        class="shadow-2xs focus:outline-hidden inline-flex items-center gap-x-2 rounded-lg border border-gray-200 bg-white px-2 py-1.5 text-sm font-medium text-gray-800 hover:bg-gray-50 focus:bg-gray-50"
    >
        <x-icon class="h-4 w-4" name="funnel" />
        Filter
    </button>

    <div
        wire:show="isVisible"
        wire:click.outside="$set('isVisible', false)"
        wire:transition
        class="absolute left-0 z-50 mt-2 min-w-48 max-w-60 overflow-visible rounded-lg border border-gray-200 bg-white shadow-md"
    >
        <span class="ml-3 text-sm font-medium text-gray-500">Status</span>
        <ul class="flex max-w-sm flex-col divide-y-[1px] divide-gray-200">
            @foreach (App\Enum\Status::cases() as $index => $status)
                <li
                    wire:key="filters-status--{{ $index }}"
                    class="-mt-px inline-flex items-center gap-x-2 bg-white px-3 py-2 text-sm font-medium text-gray-800 first:mt-0 first:rounded-t-lg last:rounded-b-lg"
                >
                    <div class="relative flex w-full items-start">
                        <div class="flex h-5 items-center">
                            <input
                                id="{{ $status->value }}"
                                wire:model="filters.status.{{ $status->value }}"
                                wire:change="updateList()"
                                type="checkbox"
                                class="rounded-sm border-gray-200 focus:ring-0 focus:ring-offset-0"
                            />
                        </div>
                        <label for="{{ $status->value }}" class="ms-3.5 block w-full text-sm text-gray-600">
                            {{ $status->value }}
                        </label>
                    </div>
                </li>
            @endforeach
        </ul>

        <span class="ml-3 text-sm font-medium text-gray-500">Priority</span>
        <ul class="flex max-w-sm flex-col divide-y-[1px] divide-gray-200">
            @foreach (App\Enum\Priority::cases() as $index => $priority)
                <li
                    wire:key="filters-priority--{{ $index }}"
                    class="-mt-px inline-flex items-center gap-x-2 bg-white px-3 py-2 text-sm font-medium text-gray-800 first:mt-0 first:rounded-t-lg last:rounded-b-lg"
                >
                    <div class="relative flex w-full items-start">
                        <div class="flex h-5 items-center">
                            <input
                                id="{{ $priority->value }}"
                                wire:model="filters.priority.{{ $priority->value }}"
                                wire:change="updateList()"
                                type="checkbox"
                                class="rounded-sm border-gray-200 focus:ring-0 focus:ring-offset-0"
                            />
                        </div>
                        <label for="{{ $priority->value }}" class="ms-3.5 block w-full text-sm text-gray-600">
                            {{ $priority->value }}
                        </label>
                    </div>
                </li>
            @endforeach
        </ul>

        <label for="deadline-from" class="ml-3 text-sm font-medium text-gray-500">Deadline from:</label>
        <div class="flex max-w-sm flex-col">
            <input
                wire:model.live="filters.deadline.from"
                class="block w-full border-x-0 border-gray-200 px-3 py-2 checked:border-none focus:border-x-0 focus:border-y focus:border-gray-200 focus:ring-0 sm:py-2 sm:text-sm"
                wire:change="updateList()"
                type="datetime-local"
                name="deadline-from"
                id="deadline-from"
            />
            @error('filters.deadline.from')
            <p class="ml-3 text-sm font-medium text-red-500">
                {{ $message }}
            </p>
            @enderror
        </div>

        <label class="ml-3 text-sm font-medium text-gray-500">Deadline to:</label>
        <div class="flex max-w-sm flex-col">
            <input
                wire:model.live="filters.deadline.to"
                wire:change="updateList()"
                class="block w-full rounded-b-lg border-x-0 border-gray-200 px-3 py-2 checked:border-none focus:border-x-0 focus:border-y focus:border-gray-200 focus:ring-0 sm:py-2 sm:text-sm"
                type="datetime-local"
                name="deadline-to"
                id="deadline-to"
            />
            @error('filters.deadline.to')
            <p class="ml-3 text-sm font-medium text-red-500">
                {{ $message }}
            </p>
            @enderror
        </div>
    </div>
</div>
