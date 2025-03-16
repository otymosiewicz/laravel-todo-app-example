<?php

use App\Livewire\Forms\TaskForm;
use App\Models\Task;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $isVisible = false;
    public TaskForm $form;

    #[On('show-modal')]
    public function showModal(): void
    {
        $this->isVisible = true;
    }

    #[On('hide-modal')]
    public function hideModal(): void
    {
        $this->isVisible = false;
    }

    public function createTask(): void
    {
        $this->validate();

        $task = auth()
            ->user()
            ->tasks()
            ->create(array_merge($this->form->all(), ['user_id' => auth()->id()]));

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
                class="shadow-2xs pointer-events-auto flex w-full flex-col rounded-xl border border-gray-200 bg-white sm:w-full"
            >
                <form wire:submit="createTask">
                    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                        <h3 class="font-bold text-gray-800">Create task</h3>
                    </div>
                    <div class="max-w-7xl overflow-y-auto p-4">
                        <p class="text-sm text-gray-500">
                            Create a new task to stay organized and track your work. Just fill in the details below.
                        </p>

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
                                    <option wire:key="status-option--{{ $index }}" value="{{ $status->value }}">
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
                                        wire:key="priority-option--{{ $index }}"
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
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endteleport
</div>

