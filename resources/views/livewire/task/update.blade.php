<?php

use App\Livewire\Forms\TaskForm;
use App\Models\Task;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $isVisible = false;
    public ?Task $task;
    public TaskForm $form;

    #[On('show-modal')]
    public function showModal(Task $task): void
    {
        $this->isVisible = true;
        $this->task = $task;
        $this->form->fill([
            'title' => $this->task->title,
            'description' => $this->task->description,
            'status' => $this->task->status,
            'priority' => $this->task->priority,
            'deadline' => $this->task->deadline ? $this->task->deadline->format('Y-m-d\TH:i') : null,
        ]);
    }

    #[On('hide-modal')]
    public function hideModal(Task $task): void
    {
        $this->isVisible = false;
    }

    public function updateTask(): void
    {
        $this->validate();
        $this->task->update($this->form->all());
        $this->redirectRoute('dashboard');
    }
}; ?>

<div>
    @teleport('body')
    <div
        @class([
            'fixed inset-0 z-50 flex items-center justify-center transition-all duration-300',
            'pointer-events-none backdrop-blur-none' => ! $isVisible,
            'pointer-events-auto bg-black/25 backdrop-blur-sm' => $isVisible,
        ])
    >
        <div
            wire:show="isVisible"
            wire:click.outside="$set('isVisible', false)"
            wire:transition
            class="m-3 flex h-auto items-center justify-center sm:mx-auto sm:w-full sm:max-w-2xl"
        >
            <div
                class="shadow-2xs pointer-events-auto flex flex-col rounded-xl border border-gray-200 bg-white sm:w-full"
            >
                <form wire:submit="updateTask">
                    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                        <h3 class="font-bold text-gray-800">Edit task</h3>
                    </div>
                    <div class="max-w-7xl overflow-y-auto p-4">
                        <div class="space-y-2">
                            <label for="title" class="mt-2.5 inline-block text-sm font-medium text-gray-800">
                                Title
                            </label>
                            <input
                                id="title"
                                type="text"
                                name="title"
                                wire:model="form.title"
                                class="shadow-2xs block w-full rounded-lg border-gray-200 px-3 py-1.5 pe-11 checked:border-blue-500 focus:border-blue-500 focus:ring-blue-500 sm:py-2 sm:text-sm"
                                placeholder="Title"
                            />

                            @error('form.title')
                            <p class="text-sm text-red-500">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="mt-2.5 inline-block text-sm font-medium text-gray-800">Description</label>

                            <textarea
                                class="block w-full rounded-lg border-gray-200 px-3 py-1.5 focus:border-blue-500 focus:ring-blue-500 sm:py-2 sm:text-sm"
                                rows="6"
                                wire:model="form.description"
                                placeholder="lorem ipsum..."
                            ></textarea>

                            @error('form.description')
                            <p class="text-sm text-red-500">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="mt-2.5 inline-block text-sm font-medium text-gray-800">Status</label>

                            <select
                                id="status"
                                name="status"
                                class="block w-full rounded-lg border-gray-200 px-3 py-1.5 focus:border-blue-500 focus:ring-blue-500 sm:py-2 sm:text-sm"
                                wire:model="form.status"
                            >
                                <option value="" disabled selected>Choose an option</option>
                                @foreach (\App\Enum\Status::cases() as $index => $status)
                                    <option
                                        wire:key="update-status-option--{{ $index }}"
                                        value="{{ $status->value }}"
                                    >
                                        {{ $status->value }}
                                    </option>
                                @endforeach
                            </select>

                            @error('form.status')
                            <p class="text-sm text-red-500">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="mt-2.5 inline-block text-sm font-medium text-gray-800">Priority</label>

                            <select
                                id="priority"
                                name="priority"
                                class="block w-full rounded-lg border-gray-200 px-3 py-1.5 focus:border-blue-500 focus:ring-blue-500 sm:py-2 sm:text-sm"
                                wire:model="form.priority"
                            >
                                <option value="" disabled selected>Choose an option</option>
                                @foreach (\App\Enum\Priority::cases() as $index => $priority)
                                    <option
                                        wire:key="update-priority-option--{{ $index }}"
                                        value="{{ $priority->value }}"
                                    >
                                        {{ $priority->value }}
                                    </option>
                                @endforeach
                            </select>

                            @error('form.priority')
                            <p class="text-sm text-red-500">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="mt-2.5 inline-block text-sm font-medium text-gray-800">Due date</label>

                            <input
                                class="shadow-2xs block w-full rounded-lg border-gray-200 px-3 py-1.5 checked:border-blue-500 focus:border-blue-500 focus:ring-blue-500 sm:py-2 sm:text-sm"
                                type="datetime-local"
                                id="deadline"
                                name="deadline"
                                wire:model="form.deadline"
                            />

                            @error('form.deadline')
                            <p class="text-sm text-red-500">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-x-2 border-t border-gray-200 px-4 py-3">
                        <button
                            wire:click="$set('isVisible', false)"
                            type="button"
                            class="shadow-2xs focus:outline-hidden inline-flex items-center gap-x-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-50 focus:bg-gray-50"
                        >
                            Close
                        </button>
                        <button
                            type="submit"
                            class="focus:outline-hidden inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700"
                        >
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endteleport
</div>
