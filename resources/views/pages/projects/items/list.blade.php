@extends('layouts')

@section('content')
    <!-- LIST PAGE -->
    <div id="list" class="page">
        <div class="page-header">
            <h1>Assignments</h1>

            <div class="header-actions">
                <a href="{{ route('projectmng.create') }}" class="btn btn-primary">
                    ➕ Create New Assignment
                </a>
            </div>
        </div>

        @include('design.includes.alert')

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
                <button class="tab-btn active" data-tab="all" onclick="switchTab(event, 'all')">
                    All <span class="tab-badge" id="badge-all">{{ $counts['all'] }}</span>
                </button>
                <button class="tab-btn" data-tab="pending" onclick="switchTab(event, 'pending')">
                    Pending <span class="tab-badge" id="badge-pending">{{ $counts['pending'] }}</span>
                </button>
                <button class="tab-btn" data-tab="processing" onclick="switchTab(event, 'processing')">
                    Processing <span class="tab-badge" id="badge-processing">{{ $counts['processing'] }}</span>
                </button>
                <button class="tab-btn" data-tab="completed" onclick="switchTab(event, 'completed')">
                    Completed <span class="tab-badge" id="badge-completed">{{ $counts['completed'] }}</span>
                </button>
                <button class="tab-btn" data-tab="on-hold" onclick="switchTab(event, 'on-hold')">
                    On Hold <span class="tab-badge" id="badge-on-hold">{{ $counts['on-hold'] }}</span>
                </button>
            </div>

            <!-- All Tab Contents - Load from Server -->
            <div id="all" class="tab-content active">
                <div class="tab-loading">Loading...</div>
            </div>

            <div id="pending" class="tab-content">
                <div class="tab-loading">Loading...</div>
            </div>

            <div id="processing" class="tab-content">
                <div class="tab-loading">Loading...</div>
            </div>

            <div id="completed" class="tab-content">
                <div class="tab-loading">Loading...</div>
            </div>

            <div id="on-hold" class="tab-content">
                <div class="tab-loading">Loading...</div>
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

        .tab-loading {
            text-align: center;
            padding: 40px;
            color: #718096;
            font-size: 14px;
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
