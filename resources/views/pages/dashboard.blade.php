@extends('layouts')

@section('content')
    <!-- DASHBOARD PAGE -->
    <div id="dashboard" class="page active">
        <div class="page-header">
            <h1>Dashboard Overview</h1>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Items</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-trend">
                    @if ($trends['total'] > 0)
                        ↑ {{ abs($trends['total']) }}% from last month
                    @elseif($trends['total'] < 0)
                        ↓ {{ abs($trends['total']) }}% from last month
                    @else
                        → No change from last month
                    @endif
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-label">Pending</div>
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-trend">
                    @if ($trends['pending'] > 0)
                        ↑ {{ abs($trends['pending']) }}% from last month
                    @elseif($trends['pending'] < 0)
                        ↓ {{ abs($trends['pending']) }}% from last month
                    @else
                        → No change from last month
                    @endif
                </div>
            </div>

            <div class="stat-card" style="border-left-color: #3182ce;">
                <div class="stat-label">Processing</div>
                <div class="stat-value">{{ $stats['processing'] }}</div>
                <div class="stat-trend">
                    @if ($trends['processing'] > 0)
                        ↑ {{ abs($trends['processing']) }}% from last month
                    @elseif($trends['processing'] < 0)
                        ↓ {{ abs($trends['processing']) }}% from last month
                    @else
                        → No change from last month
                    @endif
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-label">Completed</div>
                <div class="stat-value">{{ $stats['completed'] }}</div>
                <div class="stat-trend">
                    @if ($trends['completed'] > 0)
                        ↑ {{ abs($trends['completed']) }}% from last month
                    @elseif($trends['completed'] < 0)
                        ↓ {{ abs($trends['completed']) }}% from last month
                    @else
                        → No change from last month
                    @endif
                </div>
            </div>
        </div>

        <!-- Project Selector -->
        <div class="project-selector">
            <label>Select Project to View Recent Updates</label>
            <select id="dashboardProjectSelect">
                <option value="all" {{ request('project_id') == 'all' || !request('project_id') ? 'selected' : '' }}>
                    All Projects
                </option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Last Project Status (Pinned) -->
        @if ($lastStatus)
            <div class="last-status-section">
                <div class="last-status-header">
                    <span class="pin-icon">📌</span>
                    <h2>{{ $lastStatus->is_pinned ? 'Pinned Item' : 'Last Updated Item' }}</h2>
                </div>
                <div class="last-status-item">
                    <div class="last-status-content">
                        <div class="last-status-title">{{ $lastStatus->title }}</div>
                        <div class="last-status-meta">
                            {{ $lastStatus->project->name }} •
                            Updated {{ $lastStatus->updated_at->diffForHumans() }} •
                            <span class="status-badge status-{{ $lastStatus->status }}">
                                {{ ucfirst($lastStatus->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="last-status-actions">
                        @if ($lastStatus->attachments->count() > 0)
                            <span class="attachment-indicator">
                                📎 {{ $lastStatus->attachments->count() }}
                                file{{ $lastStatus->attachments->count() > 1 ? 's' : '' }}
                            </span>
                        @endif
                        <button class="icon-btn view" onclick="showDetails({{ $lastStatus->id }})"
                            title="View Details">👁️</button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Updates -->
        <div class="recent-section">
            <h2>Recent Project Updates</h2>

            @if ($recentUpdates->count() > 0)
                @foreach ($recentUpdates as $update)
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-title">
                                {{ $update->title }}
                                @if ($update->is_private)
                                    <span style="color: #f56565; font-size: 12px;">🔒</span>
                                @endif
                            </div>
                            <div class="timeline-meta">
                                {{ $update->project->name }} •
                                {{ $update->updated_at->diffForHumans() }} •
                                <span class="status-badge status-{{ $update->status }}">
                                    {{ ucfirst($update->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="timeline-actions">
                            @if ($update->attachments->count() > 0)
                                <span class="attachment-indicator">
                                    📎 {{ $update->attachments->count() }}
                                    file{{ $update->attachments->count() > 1 ? 's' : '' }}
                                </span>
                            @endif
                            <button class="icon-btn view" onclick="showDetails({{ $update->id }})"
                                title="View Details">👁️</button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state" style="margin-top: 20px;">
                    <div class="empty-state-icon">📋</div>
                    <h3>No Recent Updates</h3>
                    <p>There are no recent project updates to display</p>
                </div>
            @endif
        </div>
    </div>
@endsection
