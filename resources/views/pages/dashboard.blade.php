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
                <div class="stat-value">124</div>
                <div class="stat-trend">↑ 12% from last month</div>
            </div>
            <div class="stat-card warning">
                <div class="stat-label">Pending</div>
                <div class="stat-value">42</div>
                <div class="stat-trend">↑ 8% from last month</div>
            </div>
            <div class="stat-card" style="border-left-color: #3182ce;">
                <div class="stat-label">Processing</div>
                <div class="stat-value">38</div>
                <div class="stat-trend">↓ 5% from last month</div>
            </div>
            <div class="stat-card success">
                <div class="stat-label">Completed</div>
                <div class="stat-value">44</div>
                <div class="stat-trend">↑ 18% from last month</div>
            </div>
        </div>

        <!-- Project Selector -->
        <div class="project-selector">
            <label>Select Project to View Recent Updates</label>
            <select id="dashboardProjectSelect">
                <option value="all">All Projects</option>
                <option value="project1">Website Redesign</option>
                <option value="project2">Mobile App Development</option>
                <option value="project3">Marketing Campaign</option>
                <option value="project4">Database Migration</option>
            </select>
        </div>


        <!-- Last Project Status (Pinned) -->
        <div class="last-status-section">
            <div class="last-status-header">
                <span class="pin-icon">📌</span>
                <h2>Last Project Status Update</h2>
            </div>
            <div class="last-status-item">
                <div class="last-status-content">
                    <div class="last-status-title">Critical Security Patch Deployment</div>
                    <div class="last-status-meta">
                        Database Migration • Updated 30 minutes ago • <span
                            class="status-badge status-processing">Processing</span>
                    </div>
                </div>
                <div class="last-status-actions">
                    <span class="attachment-indicator">📎 4 files</span>
                    <button class="icon-btn view" onclick="showDetails(8)" title="View Details">👁️</button>
                </div>
            </div>
        </div>

        <!-- Recent Updates -->
        <div class="recent-section">
            <h2>Recent Project Updates</h2>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-title">Homepage Design Completed</div>
                    <div class="timeline-meta">
                        Website Redesign • 2 hours ago • <span class="status-badge status-completed">Completed</span>
                    </div>
                </div>
                <div class="timeline-actions">
                    <span class="attachment-indicator">📎 8 files</span>
                    <button class="icon-btn view" onclick="showDetails(6)" title="View Details">👁️</button>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-title">API Integration Testing</div>
                    <div class="timeline-meta">
                        Mobile App Development • 5 hours ago • <span
                            class="status-badge status-processing">Processing</span>
                    </div>
                </div>
                <div class="timeline-actions">
                    <span class="attachment-indicator">📎 5 files</span>
                    <button class="icon-btn view" onclick="showDetails(4)" title="View Details">👁️</button>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-title">Content Strategy Document</div>
                    <div class="timeline-meta">
                        Marketing Campaign • Yesterday • <span class="status-badge status-pending">Pending</span>
                    </div>
                </div>
                <div class="timeline-actions">
                    <span class="attachment-indicator">📎 1 file</span>
                    <button class="icon-btn view" onclick="showDetails(2)" title="View Details">👁️</button>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="timeline-title">Database Schema Review</div>
                    <div class="timeline-meta">
                        Database Migration • 2 days ago • <span class="status-badge status-processing">Processing</span>
                    </div>
                </div>
                <div class="timeline-actions">
                    <span class="attachment-indicator">📎 2 files</span>
                    <button class="icon-btn view" onclick="showDetails(5)" title="View Details">👁️</button>
                </div>
            </div>
        </div>
    </div>
@endsection
