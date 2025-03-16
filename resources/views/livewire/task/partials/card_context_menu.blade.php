<?php

use App\Models\Task;
use Livewire\Volt\Component;

new class extends Component {
    public bool $isVisible = false;
    public string $taskId;

    public function delete(Task $task): void
    {
        $task->delete();
        $this->redirectRoute('dashboard');
    }

    public function openUpdateModal(): void
    {
        $this->isVisible = false;
        $this->dispatch('show-modal', $this->taskId)->to('task.update');
    }

    public function copyLink(Task $task): void
    {
        $url = route('shared_task', ['hash' => $task->hash]);
        $this->dispatch('copy-to-clipboard', $url);
    }
}; ?>


<div class="relative">
    <button
        wire:click="$toggle('isVisible')"
        class="inline-flex size-8 items-center justify-center gap-x-2 rounded-full border border-transparent text-gray-500 hover:bg-gray-100"
    >
        <x-icon class="size-6" name="dots-three" />
    </button>

    @if ($isVisible)
        <div
            wire:click.outside="$set('isVisible', false)"
            wire:transition
            class="absolute right-0 mt-2 min-w-48 max-w-60 rounded-lg border border-gray-200 bg-white shadow-md"
        >
            <div class="space-y-0.5 border-b border-gray-200 p-1">
                <button
                    wire:click="openUpdateModal('{{ $taskId }}')"
                    type="button"
                    class="focus:outline-hidden flex w-full items-center gap-x-3 rounded-lg px-3 py-1.5 text-[13px] text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                >
                    <x-icon class="size-3.5 shrink-0" name="pencil" />
                    Edit
                </button>
                <button
                    wire:click="delete('{{ $taskId }}')"
                    type="button"
                    class="focus:outline-hidden flex w-full items-center gap-x-3 rounded-lg px-3 py-1.5 text-[13px] text-red-800 hover:bg-gray-100 focus:bg-gray-100"
                >
                    <x-icon class="size-3.5 shrink-0" name="trash" />
                    Delete
                </button>
                <button
                    type="button"
                    wire:click="copyLink('{{$taskId}}')"
                    class="focus:outline-hidden flex w-full items-center gap-x-3 rounded-lg px-3 py-1.5 text-[13px] text-gray-800 hover:bg-gray-100 focus:bg-gray-100"
                >
                    <x-icon class="size-3.5 shrink-0" name="share-fat" />
                    Share private link
                </button>
            </div>
        </div>
    @endif
</div>

@script
<script>
    if (!window.copyToClipboardRegistered) {
        window.copyToClipboardRegistered = true;

        Livewire.on('copy-to-clipboard', (url) => {
            navigator.clipboard.writeText(url);
            alert('The link has been copied to your clipboard.');
        });
    }
</script>
@endscript

