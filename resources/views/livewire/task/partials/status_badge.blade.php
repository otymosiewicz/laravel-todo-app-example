<?php

use App\Enum\Status;
use Livewire\Volt\Component;

new class extends Component {
    public Status $status;
}; ?>

<span
    class="inline-flex border border-1 border-gray-300 items-center gap-x-1.5 py-1 px-3 rounded-full text-xs font-medium bg-gray-200 text-gray-800">
    @if($status === Status::DONE)
        <x-icon class="w-3 h-3 mr-1" name="check" />
    @endif{{$status}}
</span>

