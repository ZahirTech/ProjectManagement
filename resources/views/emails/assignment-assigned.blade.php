@component('mail::message')
    # New Assignment

    You've been assigned to a new task: **{{ $item->title }}**

    - **Project:** {{ $item->project->name }}
    - **Priority:** {{ ucfirst($item->priority) }}
    - **Status:** {{ ucfirst($item->status) }}
    @if ($item->due_date)
        - **Due Date:** {{ \Carbon\Carbon::parse($item->due_date)->format('M d, Y') }}
    @endif

    @if ($item->description)
        **Description:**
        {{ $item->description }}
    @endif

    @component('mail::button', ['url' => route('projectmng.show', $item->id)])
        View Assignment
    @endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent
