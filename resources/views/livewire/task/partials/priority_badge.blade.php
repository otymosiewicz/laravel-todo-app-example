<?php

use App\Enum\Priority;
use Livewire\Volt\Component;

new class extends Component {
    public Priority $priority;
}; ?>

<span
    @class([
        'border-1 inline-flex items-center gap-x-1.5 rounded-full border px-3 py-1 text-xs font-medium',
        'border-blue-300 bg-blue-100 text-blue-800' => $priority === Priority::LOW,
        'border-orange-300 bg-orange-100 text-orange-800' => $priority === Priority::MID,
        'border-red-300 bg-red-100 text-red-800' => $priority === Priority::HIGH,
    ])
>
    <span
        @class([
            'inline-block size-1.5 rounded-full',
            'bg-blue-800' => $priority === Priority::LOW,
            'bg-orange-800' => $priority === Priority::MID,
            'bg-red-800' => $priority === Priority::HIGH,
        ])
    ></span>
    {{ $priority }}
</span>
