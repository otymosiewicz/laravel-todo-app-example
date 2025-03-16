<?php

use App\Models\Task;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Spatie\Activitylog\Models\Activity;

new class extends Component {
    public bool $isVisible = false;
    public array $activities = [];

    #[On('show-modal')]
    public function showModal(Task $task): void
    {
        $this->activities = Activity::all()
            ->where('event', '=', 'updated')
            ->where('causer_id', '=', $task->user->id)
            ->where('subject_id', '=', $task->id)
            ->all();

        $this->isVisible = true;
    }

    #[On('hide-modal')]
    public function hideModal(): void
    {
        $this->isVisible = false;
        $this->activities = [];
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
            wire:click.outside="$dispatchSelf('hide-modal')"
            wire:transition
            class="m-3 mt-0 flex h-auto w-full items-center justify-center sm:mx-auto lg:w-full lg:max-w-4xl"
        >
            <div
                class="shadow-2xs pointer-events-auto flex w-full flex-col rounded-xl border border-gray-200 bg-white"
            >
                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                    <h3 class="font-bold text-gray-800">Change history</h3>
                </div>
                <div class="overflow-y-auto p-4">
                    @foreach ($activities as $index => $activity)
                        <div
                            wire:key="activity--{{ $activity->subject_id }}--{{$index}}"
                            @class([
                                'rounded-xl border border-transparent bg-white',
                            ])
                        >
                            <p class="inline-flex w-full items-center justify-between gap-x-3 px-5 py-4 text-start font-semibold">
                                Changes #{{ $activity->created_at }}
                            </p>
                            <div class="px-5 pb-4 flex divide-x">
                                <div class="w-full space-y-2 p-2">
                                    <p class="font-medium text-gray-800 ">Before</p>
                                    @foreach($activity->changes['old'] as $key => $change)
                                        <div class="text-sm"
                                             wire:key="old--{{$activity->subject_id}}-{{$activity->id}}">
                                            <p class="font-medium">{{Str::ucfirst($key)}}</p>
                                            <em>@if($change instanceof DateTimeInterface)
                                                    {{ $change->format('d M Y, H:i') }}
                                                @else
                                                    {{ $change }}
                                                @endif</em>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="w-full space-y-2 p-2">
                                    <p class="font-medium text-gray-800 ">After</p>
                                    @foreach($activity->changes['attributes'] as $key => $change)
                                        <div class="text-sm"
                                             wire:key="new--{{$activity->subject_id}}-{{$activity->id}}">
                                            <p class="font-medium">{{Str::ucfirst($key)}}</p>
                                            <em>@if($change instanceof DateTimeInterface)
                                                    {{ $change->format('d M Y, H:i') }}
                                                @else
                                                    {{ $change }}
                                                @endif</em>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex items-center justify-end gap-x-2 border-t border-gray-200 px-4 py-3">
                    <button
                        wire:click="$dispatchSelf('hide-modal')"
                        type="button"
                        class="shadow-2xs focus:outline-hidden inline-flex items-center gap-x-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-50 focus:bg-gray-50"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endteleport
</div>
