<!DOCTYPE html>
<html>

<body style="font-family: Arial, sans-serif; color: #2d3748; line-height: 1.6;">
    <h2>Status Updated</h2>

    <p><strong>{{ $changedBy->name }}</strong> changed the status of an assignment you created.</p>

    <ul>
        <li><strong>Assignment:</strong> {{ $item->title }}</li>
        <li><strong>Project:</strong> {{ $item->project->name }}</li>
        <li><strong>Old Status:</strong> {{ ucfirst($oldStatus) }}</li>
        <li><strong>New Status:</strong> {{ ucfirst($newStatus) }}</li>
    </ul>

    <p>
        <a href="{{ route('projectmng.show', $item->id) }}"
            style="display:inline-block;padding:10px 20px;background:#4299e1;color:#fff;text-decoration:none;border-radius:6px;">
            View Assignment
        </a>
    </p>

    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>

</html>
