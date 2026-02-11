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
            <select id="dashboardProjectSelect" name="project_id"
                onchange="window.location.href='{{ route('dashboard') }}?project_id=' + this.value">
                <option value="all" {{ !isset($projectId) || $projectId == 'all' ? 'selected' : '' }}>
                    All Projects
                </option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}"
                        {{ isset($projectId) && $projectId == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Pinned Items Section -->
        @if (isset($allPinnedItems) && $allPinnedItems->count() > 0)
            <div class="pinned-section">
                <div class="pinned-header">
                    <div class="pinned-header-left">
                        <div class="pin-icon-wrapper">
                            {{-- <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 17v5m-3-2l3-3 3 3m-8-13l6-6 6 6m-12 0v6a2 2 0 002 2h8a2 2 0 002-2V7" />
                            </svg> --}}
                            <img src="{{asset('icons/pin.png')}}" alt="" height="20px" width="20px">
                        </div>
                        <h2>Pinned Items</h2>
                        <span class="pinned-count">{{ $totalPinned }}</span>
                    </div>
                    @if ($totalPinned > 4)
                        <button class="btn-expand-new" id="togglePinnedBtn" onclick="togglePinnedItems()">
                            <span id="expandText">Show All</span>
                            <svg id="expandIcon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    @endif
                </div>

                <!-- Pinned Items Grid -->
                <div class="pinned-grid" id="pinnedGrid">
                    @foreach ($allPinnedItems->take(4) as $item)
                        <div class="pinned-card"
                            onclick="window.location.href='{{ $item instanceof \App\Models\Note ? route('notes.show', $item->id) : route('projectmng.show', $item->id) }}'">
                            <!-- Unpin Button -->

                            <button class="unpin-btn"
                                onclick="event.stopPropagation(); {{ $item instanceof \App\Models\Note ? 'toggleNotePin' : 'togglePin' }}({{ $item->id }})"
                                title="Unpin this item">
                                {{-- <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M12 17v5m-3-2l3-3 3 3m-8-13l6-6 6 6m-12 0v6a2 2 0 002 2h8a2 2 0 002-2V7" />
                                </svg> --}}

                                <img src="{{asset('icons/gps.png')}}" alt="" height="20px" width="20px">
                            </button>

                            <div class="pinned-card-header">
                                <div
                                    class="pinned-type-badge {{ $item instanceof \App\Models\Note ? 'note-badge' : 'assignment-badge' }}">
                                    @if ($item instanceof \App\Models\Note)
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                            <line x1="16" y1="13" x2="8" y2="13" />
                                            <line x1="16" y1="17" x2="8" y2="17" />
                                            <polyline points="10 9 9 9 8 9" />
                                        </svg>
                                        <span>Note</span>
                                    @else
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="9 11 12 14 22 4" />
                                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                                        </svg>
                                        <span>Task</span>
                                    @endif

                                    @if ($item instanceof \App\Models\ProjectItem)
                                        <span class="status-badge-new status-{{ $item->status }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    @endif
                                </div>
                                {{-- @if ($item instanceof \App\Models\ProjectItem)
                                    <span class="status-badge-new status-{{ $item->status }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                @endif --}}
                            </div>

                            <h3 class="pinned-card-title">{{ $item->title }}</h3>

                            <div class="pinned-card-meta">
                                <div class="meta-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                    <span>{{ $item->project->name }}</span>
                                </div>
                                <div class="meta-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <span>{{ $item->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            @if ($item instanceof \App\Models\ProjectItem && isset($item->attachments) && $item->attachments->count() > 0)
                                <div class="pinned-card-footer">
                                    <div class="attachment-badge-new">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path
                                                d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48" />
                                        </svg>
                                        <span>{{ $item->attachments->count() }}
                                            file{{ $item->attachments->count() > 1 ? 's' : '' }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Expandable Container for Additional Pinned Items -->
                @if ($totalPinned > 4)
                    <div id="expandablePinnedItems" class="expandable-pinned-container-new">
                        <div class="pinned-grid">
                            @foreach ($allPinnedItems->skip(4) as $item)
                                <div class="pinned-card"
                                    onclick="window.location.href='{{ $item instanceof \App\Models\Note ? route('notes.show', $item->id) : route('projectmng.show', $item->id) }}'">
                                    <!-- Unpin Button -->
                                    <button class="unpin-btn"
                                        onclick="event.stopPropagation(); {{ $item instanceof \App\Models\Note ? 'toggleNotePin' : 'togglePin' }}({{ $item->id }})"
                                        title="Unpin this item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"
                                            stroke="currentColor" stroke-width="2">
                                            <path
                                                d="M12 17v5m-3-2l3-3 3 3m-8-13l6-6 6 6m-12 0v6a2 2 0 002 2h8a2 2 0 002-2V7" />
                                        </svg>
                                    </button>

                                    <div class="pinned-card-header">
                                        <div
                                            class="pinned-type-badge {{ $item instanceof \App\Models\Note ? 'note-badge' : 'assignment-badge' }}">
                                            @if ($item instanceof \App\Models\Note)
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                                    <polyline points="14 2 14 8 20 8" />
                                                    <line x1="16" y1="13" x2="8" y2="13" />
                                                    <line x1="16" y1="17" x2="8" y2="17" />
                                                    <polyline points="10 9 9 9 8 9" />
                                                </svg>
                                                <span>Note</span>
                                            @else
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <polyline points="9 11 12 14 22 4" />
                                                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                                                </svg>
                                                <span>Task</span>
                                            @endif
                                        </div>
                                        @if ($item instanceof \App\Models\ProjectItem)
                                            <span class="status-badge-new status-{{ $item->status }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="pinned-card-title">{{ $item->title }}</h3>

                                    <div class="pinned-card-meta">
                                        <div class="meta-item">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2"
                                                    ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            <span>{{ $item->project->name }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12 6 12 12 16 14" />
                                            </svg>
                                            <span>{{ $item->updated_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    @if ($item instanceof \App\Models\ProjectItem && isset($item->attachments) && $item->attachments->count() > 0)
                                        <div class="pinned-card-footer">
                                            <div class="attachment-badge-new">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path
                                                        d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48" />
                                                </svg>
                                                <span>{{ $item->attachments->count() }}
                                                    file{{ $item->attachments->count() > 1 ? 's' : '' }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Recent Updates -->
        <div class="recent-section">
            <h2>Recent Project Updates</h2>

            @if (isset($recentUpdates) && $recentUpdates->count() > 0)
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
                            @if (isset($update->attachments) && $update->attachments->count() > 0)
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

    <style>
        /* ===== PINNED SECTION STYLES ===== */
        .pinned-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.2);
        }

        .pinned-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .pinned-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pin-icon-wrapper {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            backdrop-filter: blur(10px);
        }

        .pinned-header h2 {
            color: white;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .pinned-count {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .btn-expand-new {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .btn-expand-new:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-expand-new svg {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-expand-new.expanded svg {
            transform: rotate(180deg);
        }

        /* Pinned Grid */
        .pinned-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px;
        }

        /* Pinned Card */
        .pinned-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .pinned-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .pinned-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.2);
        }

        .pinned-card:hover::before {
            transform: scaleX(1);
        }

        /* Unpin Button */
        .unpin-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 32px;
            height: 32px;
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.2);
            border-radius: 8px;
            color: #dc2626;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10;
            opacity: 0;
            transform: scale(0.8);
        }

        .pinned-card:hover .unpin-btn {
            opacity: 1;
            transform: scale(1);
        }

        .unpin-btn:hover {
            /* background: #dc2626; */
            background: #fcefef;
            color: white;
            border-color: #f08888;
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 4px 12px rgba(216, 147, 147, 0.3);
        }

        .unpin-btn:active {
            transform: scale(0.95) rotate(15deg);
        }

        .unpin-btn svg {
            width: 16px;
            height: 16px;
            transform: rotate(180deg);
        }

        /* Show unpin button on mobile touch */
        @media (hover: none) and (pointer: coarse) {
            .unpin-btn {
                opacity: 0.7;
                transform: scale(1);
            }

            .pinned-card:hover .unpin-btn {
                opacity: 1;
            }
        }

        .pinned-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .pinned-type-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .pinned-type-badge.note-badge {
            background: #e6f7ff;
            color: #0284c7;
        }

        .pinned-type-badge.assignment-badge {
            background: #f0fdf4;
            color: #16a34a;
        }

        .status-badge-new {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge-new.status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-badge-new.status-processing {
            background: #dbeafe;
            color: #2563eb;
        }

        .status-badge-new.status-completed {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-badge-new.status-on-hold {
            background: #fee2e2;
            color: #dc2626;
        }

        .pinned-card-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin: 0 0 12px 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pinned-card-meta {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #64748b;
        }

        .meta-item svg {
            flex-shrink: 0;
            opacity: 0.7;
        }

        .pinned-card-footer {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }

        .attachment-badge-new {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            background: #f8fafc;
            border-radius: 6px;
            font-size: 12px;
            color: #475569;
            font-weight: 500;
        }

        .attachment-badge-new svg {
            opacity: 0.7;
        }

        /* Expandable Container */
        .expandable-pinned-container-new {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 16px;
        }

        .expandable-pinned-container-new.expanded {
            max-height: 2000px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .pinned-grid {
                grid-template-columns: 1fr;
            }

            .pinned-section {
                padding: 16px;
            }

            .pinned-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .btn-expand-new {
                width: 100%;
                justify-content: center;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .pinned-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Smooth Scroll */
        * {
            scroll-behavior: smooth;
        }
    </style>

    <script>
        function togglePinnedItems() {
            const container = document.getElementById('expandablePinnedItems');
            const expandText = document.getElementById('expandText');
            const expandIcon = document.getElementById('expandIcon');
            const toggleBtn = document.getElementById('togglePinnedBtn');

            if (container.classList.contains('expanded')) {
                container.classList.remove('expanded');
                expandText.textContent = 'Show All';
                toggleBtn.classList.remove('expanded');
            } else {
                container.classList.add('expanded');
                expandText.textContent = 'Show Less';
                toggleBtn.classList.add('expanded');
            }
        }
    </script>
@endsection
