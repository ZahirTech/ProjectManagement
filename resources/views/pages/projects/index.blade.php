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

    @if ($projects->count() > 0)
        <!-- FILTER BAR (mobile) -->
        <div class="filter-bar">
            <div class="filter-select-wrap">
                <select id="statusFilter" class="filter-select" onchange="applyProjectFilter()">
                    <option value="all">All statuses</option>
                    <option value="active">Active</option>
                    <option value="on_hold">On Hold</option>
                    <option value="completed">Completed</option>
                    <option value="archived">Archived</option>
                </select>
                <span class="filter-select-icon">▾</span>
            </div>
        </div>
    @endif

    <!-- DESKTOP / TABLET TABLE -->
    <div class="card table-view">
        <div class="card-body table-scroll">
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
                            <tr data-status="{{ $project->status }}">
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

    <!-- MOBILE CARD LIST -->
    @if ($projects->count() > 0)
        <div class="project-cards">
            @foreach ($projects as $project)
                <div class="project-card" data-status="{{ $project->status }}">
                    <div class="project-card-top">
                        <div class="project-card-heading">
                            <span class="project-card-id">#{{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</span>
                            <span class="project-card-name">{{ $project->name }}</span>
                        </div>
                        <button class="icon-btn edit"
                            onclick="window.location.href='{{ route('projects.edit', $project->id) }}'"
                            title="Edit Project">
                            ✏️
                        </button>
                    </div>

                    @if ($project->description)
                        <p class="project-card-description">{{ Str::limit($project->description, 80) }}</p>
                    @endif

                    <div class="project-card-footer">
                        <select class="status-select status-{{ $project->status }}"
                            onchange="updateProjectStatus(this, {{ $project->id }})"
                            data-old-status="{{ $project->status }}">
                            <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="on_hold" {{ $project->status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="archived" {{ $project->status === 'archived' ? 'selected' : '' }}>Archived
                            </option>
                        </select>

                        <span class="project-card-meta">
                            {{ $project->items_count }} items · {{ $project->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="noResults" class="empty-state" style="display:none;">
            <div class="empty-state-icon">🔍</div>
            <p>No projects match this filter</p>
        </div>
    @endif

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
                        // Keep every select for this project (table + card) and the filter in sync
                        syncProjectStatus(projectId, newStatus);
                    } else {
                        showNotification('Failed to update status', 'error');
                        revertStatus(selectElement, oldStatus);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred while updating status', 'error');
                    revertStatus(selectElement, oldStatus);
                });
        }

        function revertStatus(selectElement, oldStatus) {
            selectElement.value = oldStatus;
            selectElement.classList.remove('status-active', 'status-on_hold', 'status-completed', 'status-archived');
            selectElement.classList.add('status-' + oldStatus);
        }

        // The same project appears twice (table row + mobile card); keep both in sync
        // and update the data-status used by the mobile filter.
        function syncProjectStatus(projectId, newStatus) {
            document.querySelectorAll(`[onchange*="updateProjectStatus(this, ${projectId})"]`)
                .forEach(select => {
                    select.value = newStatus;
                    select.dataset.oldStatus = newStatus;
                    select.classList.remove('status-active', 'status-on_hold', 'status-completed', 'status-archived');
                    select.classList.add('status-' + newStatus);
                    const row = select.closest('[data-status]');
                    if (row) row.dataset.status = newStatus;
                });
            applyProjectFilter();
        }

        function applyProjectFilter() {
            const filterEl = document.getElementById('statusFilter');
            if (!filterEl) return;
            const status = filterEl.value;

            const rows = document.querySelectorAll('.project-cards .project-card, .data-table tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const visible = status === 'all' || row.dataset.status === status;
                row.style.display = visible ? '' : 'none';
                if (visible) visibleCount++;
            });

            const noResults = document.getElementById('noResults');
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? '' : 'none';
            }
        }
    </script>

    <style>
        /* ---- Filter bar ---- */
        .filter-bar {
            display: flex;
            gap: 10px;
            margin: 16px 0;
        }

        .filter-select-wrap {
            position: relative;
            flex: 1;
            min-width: 0;
            max-width: 260px;
        }

        .filter-select-wrap .filter-select {
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            padding: 10px 30px 10px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            background: #fff;
            font-size: 0.875rem;
            color: #2d3748;
            font-weight: 500;
        }

        .filter-select-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.75rem;
            color: #a0aec0;
            pointer-events: none;
        }

        /* ---- View switching: table on desktop, cards on mobile ----
                   !important is used here defensively: if the page's global stylesheet
                   sets display on .table-view/.project-cards/.data-table elsewhere,
                   those rules can win the cascade and silently block this switch. */
        .project-cards {
            display: none !important;
        }

        @media (max-width: 767px) {
            .table-view {
                display: none !important;
            }

            .filter-bar {
                margin: 12px 0 16px;
            }

            .filter-select-wrap {
                max-width: none;
            }

            .project-cards {
                display: flex !important;
                flex-direction: column;
                gap: 10px;
            }
        }

        /* ---- Mobile project card ---- */
        .project-card {
            background: #fff;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 14px 16px;
        }

        .project-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .project-card-heading {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .project-card-id {
            font-size: 0.75rem;
            color: #a0aec0;
            font-weight: 600;
        }

        .project-card-name {
            font-size: 0.9375rem;
            font-weight: 600;
            color: #2d3748;
            word-break: break-word;
        }

        .project-card-description {
            margin: 8px 0 0;
            font-size: 0.8125rem;
            color: #718096;
        }

        .project-card-footer {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f7fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .project-card-meta {
            font-size: 0.75rem;
            color: #a0aec0;
        }

        /* ---- Colored status pill (applies to the select in both the table
                   and the mobile card, so status is visually distinct everywhere,
                   regardless of whether a global stylesheet already styles it) ---- */
        .status-select {
            appearance: auto;
            -webkit-appearance: menulist;
            border: none !important;
            border-radius: 999px !important;
            padding: 5px 10px !important;
            font-size: 0.8125rem !important;
            font-weight: 600 !important;
            cursor: pointer;
        }

        .status-select.status-active {
            background-color: #ebf8ff !important;
            color: #2b6cb0 !important;
        }

        .status-select.status-on_hold {
            background-color: #feebc8 !important;
            color: #9c4221 !important;
        }

        .status-select.status-completed {
            background-color: #d4edda !important;
            color: #155724 !important;
        }

        .status-select.status-archived {
            background-color: #edf2f7 !important;
            color: #4a5568 !important;
        }
    </style>
@endsection
