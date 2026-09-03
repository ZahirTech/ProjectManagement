@component('mail::message')
    # Status Updated

    **{{ $changedBy->name }}** changed the status of an assignment you created.

    - **Assignment:** {{ $item->title }}
    - **Project:** {{ $item->project->name }}
    - **Old Status:** {{ ucfirst($oldStatus) }}
    - **New Status:** {{ ucfirst($newStatus) }}

    @component('mail::button', ['url' => route('projectmng.show', $item->id)])
        View Assignment
    @endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent
