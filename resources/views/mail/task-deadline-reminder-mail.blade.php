{{--@formatter:off--}}
<x-mail::message>
    # Task Deadline Reminder

    Hi {{ $task->user->name }},

    Your task **{{ $task->title }}** is due in **1 day** at **{{ $task->deadline->format('H:i') }}**.

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
