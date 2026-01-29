@extends('layouts')

@section('content')
    <div class="page-header">
        <div>
            <h1>Manage Projects</h1>
            <p class="page-description">Create and manage your projects</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                ➕ Create New Project
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($projects->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Items Count</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>#{{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $project->name }}</td>
                                <td>{{ Str::limit($project->description, 50) ?: '-' }}</td>
                                <td>
                                    <select class="status-select status-{{ $project->status }}"
                                        onchange="updateProjectStatus(this, {{ $project->id }})"
                                        data-old-status="{{ $project->status }}">
                                        <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="on_hold" {{ $project->status === 'on_hold' ? 'selected' : '' }}>On
                                            Hold</option>
                                        <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="archived" {{ $project->status === 'archived' ? 'selected' : '' }}>
                                            Archived</option>
                                    </select>
                                </td>
                                <td>{{ $project->items_count }} items</td>
                                <td>{{ $project->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn edit"
                                            onclick="window.location.href='{{ route('projects.edit', $project->id) }}'"
                                            title="Edit Project">
                                            ✏️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📁</div>
                    <h3>No Projects Found</h3>
                    <p>Create your first project to get started</p>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary" style="margin-top: 20px;">
                        ➕ Create New Project
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    <script>
        function updateProjectStatus(selectElement, projectId) {
            const newStatus = selectElement.value;
            const oldStatus = selectElement.dataset.oldStatus;
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            if (!csrfToken) {
                console.error('CSRF token not found');
                alert('Error: CSRF token not found');
                return;
            }

            // Update the select element's class immediately for UI feedback
            selectElement.classList.remove('status-active', 'status-on_hold', 'status-completed', 'status-archived');
            selectElement.classList.add('status-' + newStatus);

            // Send AJAX request to update status
            fetch(`/projects/${projectId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        selectElement.dataset.oldStatus = newStatus;
                    } else {
                        showNotification('Failed to update status', 'error');
                        // Revert
                        selectElement.value = oldStatus;
                        selectElement.classList.remove('status-active', 'status-on_hold', 'status-completed',
                            'status-archived');
                        selectElement.classList.add('status-' + oldStatus);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred while updating status', 'error');
                    // Revert
                    selectElement.value = oldStatus;
                    selectElement.classList.remove('status-active', 'status-on_hold', 'status-completed',
                        'status-archived');
                    selectElement.classList.add('status-' + oldStatus);
                });
        }
    </script>
@endsection
