<x-guest-layout>
    <div
        class="shadow-2xs flex flex-col"
    >
        <div class="flex items-center justify-between rounded-t-xl border-b border-gray-200 px-3 py-1.5 md:px-4">
            <h3 class="text-md font-semibold text-gray-800">
                {{ $task->title }}
            </h3>
        </div>

        <div class="space-y-2 px-4 py-2">
            <p class="text-xs font-bold text-gray-700">
                Author: {{ $task->user->name }}
            </p>

            <div class="mt-1 flex text-xs font-medium text-gray-500">
                <x-icon class="mr-1 h-4 w-4" name="calendar-dot" />

                @if ($task->deadline !== null)
                    <p>{{ $task->deadline }}</p>
                @else
                    <p>No deadline set</p>
                @endif
            </div>

            <div class="inline-flex items-center gap-2">
                <livewire:task.partials.priority_badge :priority="$task->priority" :key="$task->id" />
                <livewire:task.partials.status_badge :status="$task->status" :key="$task->id" />
            </div>
            @if ($task->description !== null)
                <p class="pt-2 text-sm/tight text-gray-500">
                    {!! strip_tags(\Illuminate\Support\Str::inlineMarkdown($task->description), '<b><i><u><a><ul><ol><li><p><strong><em>') !!}
                </p>
            @else
                <p class="text-sm text-gray-500">No description provided yet.</p>
            @endif
        </div>
    </div>
</x-guest-layout>
