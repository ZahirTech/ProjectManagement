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

        <!-- Compact Filter Bar -->
        <div class="filter-bar">
            <div class="filter-left">
                <select id="listProjectSelect" class="compact-select">
                    <option value="all">All Projects</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-right">
                <label class="toggle-switch">
                    <input type="checkbox" id="myItemsToggle" {{ request('my_items') ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                    <span class="toggle-label">My Assignments</span>
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
                                                <button class="icon-btn pin {{ $item->is_pinned ? 'pinned' : '' }}"
                                                    onclick="togglePin({{ $item->id }})"
                                                    title="{{ $item->is_pinned ? 'Unpin' : 'Pin to Dashboard' }}">
                                                    📌
                                                </button>
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

        /* Compact Filter Bar */
        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            gap: 15px;
        }

        .filter-left {
            flex: 0 0 auto;
        }

        .compact-select {
            padding: 8px 32px 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: white;
            font-size: 14px;
            cursor: pointer;
            min-width: 200px;
        }

        .filter-right {
            flex: 0 0 auto;
        }

        /* Toggle Switch */
        .toggle-switch {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }

        .toggle-switch input[type="checkbox"] {
            display: none;
        }

        .toggle-slider {
            position: relative;
            width: 44px;
            height: 24px;
            background: #cbd5e0;
            border-radius: 24px;
            transition: background 0.3s;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: white;
            top: 3px;
            left: 3px;
            transition: transform 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .toggle-switch input:checked+.toggle-slider {
            background: #4299e1;
        }

        .toggle-switch input:checked+.toggle-slider::before {
            transform: translateX(20px);
        }

        .toggle-label {
            font-size: 14px;
            font-weight: 500;
            color: #2d3748;
        }

        /* Pin Button */
        .icon-btn.pin {
            opacity: 0.4;
            transition: all 0.2s;
        }

        .icon-btn.pin:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .icon-btn.pin.pinned {
            opacity: 1;
            color: #f56565;
            animation: pinPulse 0.6s ease-in-out;
        }

        @keyframes pinPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .compact-select {
                width: 100%;
                min-width: auto;
            }

            .filter-right {
                width: 100%;
            }

            .toggle-switch {
                justify-content: space-between;
            }
        }
    </style>
@endsection
