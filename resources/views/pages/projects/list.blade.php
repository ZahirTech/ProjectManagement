@extends('layouts')

@section('content')
    <!-- LIST PAGE -->
    <div id="list" class="page">
        <div class="page-header">
            <h1>All Project Items</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Project Selector and My Items Toggle -->
        <div class="filter-controls" style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px;">
            <div class="project-selector" style="flex: 1;">
                <label>Filter by Project</label>
                <select id="listProjectSelect">
                    <option value="all" {{ request('project_id') == 'all' ? 'selected' : '' }}>All Projects</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="my-items-toggle">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="myItemsToggle" {{ request('my_items') ? 'checked' : '' }}>
                    <span>Show only my assigned items</span>
                </label>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs-container">
            <div class="tabs-header">
                <button class="tab-btn {{ !request('status') || request('status') == 'pending' ? 'active' : '' }}"
                    onclick="switchTab(event, 'pending')">
                    Pending <span class="tab-badge">{{ $counts['pending'] }}</span>
                </button>
                <button class="tab-btn {{ request('status') == 'processing' ? 'active' : '' }}"
                    onclick="switchTab(event, 'processing')">
                    Processing <span class="tab-badge">{{ $counts['processing'] }}</span>
                </button>
                <button class="tab-btn {{ request('status') == 'completed' ? 'active' : '' }}"
                    onclick="switchTab(event, 'completed')">
                    Completed <span class="tab-badge">{{ $counts['completed'] }}</span>
                </button>
                <button class="tab-btn {{ request('status') == 'on-hold' ? 'active' : '' }}"
                    onclick="switchTab(event, 'on-hold')">
                    On Hold <span class="tab-badge">{{ $counts['on-hold'] }}</span>
                </button>
            </div>

            <!-- Dynamic Tab Content -->
            <div id="{{ request('status', 'pending') }}" class="tab-content active">
                @if ($items->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Due Date</th>
                                <th>Assigned To</th>
                                <th>Attachments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>#{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        {{ $item->title }}
                                        @if ($item->is_private)
                                            <span style="color: #f56565; font-size: 12px;">🔒 Private</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->project->name }}</td>
                                    <td>
                                        <select class="status-select status-{{ $item->status }}"
                                            onchange="updateStatus(this, {{ $item->id }})">
                                            <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="processing"
                                                {{ $item->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>
                                                Completed</option>
                                            <option value="on-hold" {{ $item->status == 'on-hold' ? 'selected' : '' }}>On
                                                Hold</option>
                                        </select>
                                    </td>
                                    <td>{{ ucfirst($item->priority) }}</td>
                                    <td>{{ $item->due_date ? $item->due_date->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $item->assignedUser ? $item->assignedUser->name : 'Not Assigned' }}</td>
                                    <td>
                                        @if ($item->attachments->count() > 0)
                                            <span class="attachment-indicator">
                                                📎 {{ $item->attachments->count() }}
                                                file{{ $item->attachments->count() > 1 ? 's' : '' }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="icon-btn view" onclick="showDetails({{ $item->id }})"
                                                title="View Details">👁️</button>
                                            @if ($item->created_by == auth()->id())
                                                <button class="icon-btn edit"
                                                    onclick="window.location.href='/project_manage/{{ $item->id }}/edit'"
                                                    title="Edit">✏️</button>
                                                <button class="icon-btn delete" onclick="deleteItem({{ $item->id }})"
                                                    title="Delete">🗑️</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing {{ $items->firstItem() }}-{{ $items->lastItem() }} of {{ $items->total() }} items
                        </div>
                        <div class="pagination">
                            {{ $items->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">📋</div>
                        <h3>No Items Found</h3>
                        <p>There are no items matching your current filters</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .filter-controls {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .my-items-toggle input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
    </style>
@endsection
