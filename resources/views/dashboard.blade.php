<x-app-layout>
    <div class="mx-auto mt-6 max-w-7xl text-xl font-semibold leading-tight text-gray-800 sm:px-6 lg:px-8">
        {{ __('Your tasks') }}
    </div>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <livewire:task.list />
            </div>
        </div>
    </div>
</x-app-layout>
