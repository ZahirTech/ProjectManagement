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

        <!-- Compact Filter Bar (normal flow at first; becomes a slide-in overlay once tabs pin) -->
        <div class="filter-bar" id="filterBar">
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
            <div class="tabs-header" id="tabsHeader">
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

            <!-- Spacer: gets height only once filter bar + tabs leave normal flow -->
            <div id="tabsHeaderSpacer" style="height:0;"></div>

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

    <script>
        function initStickyMobileHeader() {
            const filterBar = document.getElementById('filterBar');
            const tabsHeader = document.getElementById('tabsHeader');
            const spacer = document.getElementById('tabsHeaderSpacer');
            if (!filterBar || !tabsHeader || !spacer) return;

            const isMobile = () => window.innerWidth <= 767;
            let originalOffsetTop = null;
            let lastScrollY = window.scrollY;
            let filterBarHidden = false;

            // If your topbar is fixed/sticky, keep everything below it.
            // Adjust '.topbar' to match your actual topbar element's class.
            function getTopOffset() {
                const topbar = document.querySelector('.topbar');
                if (!topbar) return 0;
                const style = window.getComputedStyle(topbar);
                if (style.position === 'fixed' || style.position === 'sticky') {
                    return topbar.getBoundingClientRect().height;
                }
                return 0;
            }

            function resetToNormalFlow() {
                tabsHeader.classList.remove('is-fixed');
                tabsHeader.style.top = '';
                filterBar.classList.remove('is-fixed', 'is-hidden');
                filterBar.style.top = '';
                spacer.style.height = '0px';
                originalOffsetTop = null;
                filterBarHidden = false;
            }

            function onScroll() {
                if (!isMobile()) {
                    resetToNormalFlow();
                    return;
                }

                const topOffset = getTopOffset();

                // Measure the tabs' natural resting position once, before anything is fixed.
                if (originalOffsetTop === null) {
                    const rect = tabsHeader.getBoundingClientRect();
                    originalOffsetTop = rect.top + window.scrollY - topOffset;
                }

                const scrollY = window.scrollY;
                const scrollingDown = scrollY > lastScrollY;
                const delta = Math.abs(scrollY - lastScrollY);

                if (scrollY >= originalOffsetTop) {
                    // Past the tabs' natural position: pin the tabs bar.
                    if (!tabsHeader.classList.contains('is-fixed')) {
                        const combinedHeight = filterBar.offsetHeight + tabsHeader.offsetHeight;
                        spacer.style.height = combinedHeight + 'px';
                        tabsHeader.classList.add('is-fixed');
                        filterBar.classList.add('is-fixed');
                        filterBar.style.top = topOffset + 'px';
                        tabsHeader.style.top = (topOffset + filterBar.offsetHeight) + 'px';
                        filterBarHidden = false;
                    }

                    // Scroll-direction driven peek: scrolling up reveals the filter
                    // bar as an overlay above the pinned tabs; scrolling down hides it.
                    if (delta > 5) {
                        if (scrollingDown && !filterBarHidden) {
                            filterBar.classList.add('is-hidden');
                            tabsHeader.style.top = topOffset + 'px';
                            filterBarHidden = true;
                        } else if (!scrollingDown && filterBarHidden) {
                            filterBar.classList.remove('is-hidden');
                            tabsHeader.style.top = (topOffset + filterBar.offsetHeight) + 'px';
                            filterBarHidden = false;
                        }
                    }
                } else {
                    // Back near the top of the page: everything returns to normal flow.
                    resetToNormalFlow();
                }

                lastScrollY = scrollY;
            }

            window.addEventListener('scroll', onScroll, {
                passive: true
            });
            window.addEventListener('resize', function() {
                originalOffsetTop = null;
                onScroll();
            });

            onScroll();
        }

        document.addEventListener('DOMContentLoaded', initStickyMobileHeader);
    </script>

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

        /* Responsive (desktop-to-tablet filter bar stacking) */
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

        /* Mobile assignment cards (hidden on desktop) */
        .assignment-cards {
            display: none;
        }

        .assignment-card {
            display: flex;
            flex-direction: column;
            background: #fff;
            border: 1px solid #edf2f7;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .assignment-card-top {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .assignment-card-icon {
            flex-shrink: 0;
            font-size: 18px;
            line-height: 1.4;
            margin-top: 2px;
        }

        .assignment-card-body {
            flex: 1;
            min-width: 0;
        }

        .assignment-card-title {
            margin: 0;
            font-size: 0.9375rem;
            font-weight: 500;
            color: #2d3748;
            line-height: 1.4;
        }

        .assignment-card-meta-row {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            font-size: 0.75rem;
            color: #a0aec0;
        }

        .assignment-card-assignee {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 6px;
            font-size: 0.75rem;
            color: #718096;
        }

        .assignee-icon {
            font-size: 0.8125rem;
        }

        .assignment-card-footer {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f7fafc;
            display: flex;
        }

        .assignment-card-footer .status-select-wrap {
            position: relative;
            width: 100%;
        }

        .assignment-card-footer .status-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 100%;
            padding: 6px 28px 6px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #2d3748;
        }

        .assignment-card-footer .status-select-arrow {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.7rem;
            color: #a0aec0;
            pointer-events: none;
        }

        /* Mobile-only: card view + collapsible filter bar + pinned tabs */
        @media (max-width: 767px) {
            .table-view {
                display: none;
            }

            .assignment-cards {
                display: block;
            }

            .tabs-header {
                background: #fff;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                white-space: nowrap;
            }

            .filter-bar.is-fixed {
                position: fixed;
                left: 0;
                right: 0;
                z-index: 1000;
                margin-bottom: 0;
                border-radius: 0;
                transition: transform 0.25s ease;
                transform: translateY(0);
            }

            .filter-bar.is-fixed.is-hidden {
                transform: translateY(-100%);
            }

            .tabs-header.is-fixed {
                position: fixed;
                left: 0;
                right: 0;
                z-index: 999;
                transition: top 0.25s ease;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            }
        }
    </style>
@endsection
