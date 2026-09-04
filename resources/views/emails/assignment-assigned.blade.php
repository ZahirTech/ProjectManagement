<!DOCTYPE html>
<html>

<body style="font-family: Arial, sans-serif; color: #2d3748; line-height: 1.6;">
    <h2>New Assignment</h2>

    <p>You've been assigned to a new task: <strong>{{ $item->title }}</strong></p>

    <ul>
        <li><strong>Project:</strong> {{ $item->project->name }}</li>
        <li><strong>Priority:</strong> {{ ucfirst($item->priority) }}</li>
        <li><strong>Status:</strong> {{ ucfirst($item->status) }}</li>
        @if ($item->due_date)
            <li><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($item->due_date)->format('M d, Y') }}</li>
        @endif
    </ul>

    @if ($item->description)
        <p><strong>Description:</strong><br>{{ $item->description }}</p>
    @endif

    <p>
        <a href="{{ route('projectmng.show', $item->id) }}"
            style="display:inline-block;padding:10px 20px;background:#4299e1;color:#fff;text-decoration:none;border-radius:6px;">
            View Assignment
        </a>
    </p>

    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>

</html>
