<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management System</title>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
</head>

<body>
    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" id="menuToggle" onclick="toggleSidebar()">☰</button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <h2>📊 PM System</h2>
            </div>
            <button class="close-sidebar" onclick="closeSidebar()">✕</button>
        </div>
        <ul class="nav-menu">
            <li class="nav-item active" onclick="showPage('dashboard')">
                <span class="nav-icon">🏠</span>
                <span>Dashboard</span>
            </li>
            <li class="nav-item" onclick="showPage('create')">
                <span class="nav-icon">➕</span>
                <span>Create New</span>
            </li>
            <li class="nav-item" onclick="showPage('list')">
                <span class="nav-icon">📋</span>
                <span>All Items</span>
            </li>
        </ul>
        <div class="logout-nav" onclick="logout()">
            <span class="nav-icon">🚪</span>
            <span>Logout</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">

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
                            Website Redesign • 2 hours ago • <span
                                class="status-badge status-completed">Completed</span>
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
                            Database Migration • 2 days ago • <span
                                class="status-badge status-processing">Processing</span>
                        </div>
                    </div>
                    <div class="timeline-actions">
                        <span class="attachment-indicator">📎 2 files</span>
                        <button class="icon-btn view" onclick="showDetails(5)" title="View Details">👁️</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- CREATE PAGE -->
        <div id="create" class="page">
            <div class="page-header">
                <h1>Create New Item</h1>
            </div>

            <div class="form-card">
                <form id="createForm">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Select Project *</label>
                            <select required>
                                <option value="">-- Choose Project --</option>
                                <option value="project1">Website Redesign</option>
                                <option value="project2">Mobile App Development</option>
                                <option value="project3">Marketing Campaign</option>
                                <option value="project4">Database Migration</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Item Title *</label>
                            <input type="text" placeholder="Enter item title" required>
                        </div>

                        <div class="form-group">
                            <label>Status *</label>
                            <select required>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="on-hold">On Hold</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Priority</label>
                            <select>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Due Date</label>
                            <input type="date">
                        </div>

                        <div class="form-group">
                            <label>Assigned To</label>
                            <input type="text" placeholder="Enter assignee name">
                        </div>

                        <div class="form-group">
                            <label>Progress (%)</label>
                            <input type="number" min="0" max="100" value="0">
                        </div>

                        <div class="form-group full-width">
                            <label>Description</label>
                            <textarea placeholder="Enter detailed description..."></textarea>
                        </div>

                        <div class="form-group full-width">
                            <label>Attach Files</label>
                            <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                                <input type="file" id="fileInput" multiple>
                                <div class="file-icon">📎</div>
                                <div>Click to upload or drag and drop</div>
                                <div style="font-size: 12px; color: #a0aec0; margin-top: 5px;">
                                    PDF, DOC, XLS, Images (Max 10MB each)
                                </div>
                            </div>
                            <div class="uploaded-files-list" id="uploadedFilesList">
                                <!-- Uploaded files will appear here -->
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Create Item</button>
                </form>
            </div>
        </div>

        <!-- LIST PAGE -->
        <div id="list" class="page">
            <div class="page-header">
                <h1>All Project Items</h1>
            </div>

            <!-- Project Selector -->
            <div class="project-selector">
                <label>Filter by Project</label>
                <select id="listProjectSelect">
                    <option value="all">All Projects</option>
                    <option value="project1">Website Redesign</option>
                    <option value="project2">Mobile App Development</option>
                    <option value="project3">Marketing Campaign</option>
                    <option value="project4">Database Migration</option>
                </select>
            </div>

            <!-- Tabs -->
            <div class="tabs-container">
                <div class="tabs-header">
                    <button class="tab-btn active" onclick="switchTab(event, 'pending')">
                        Pending <span class="tab-badge">42</span>
                    </button>
                    <button class="tab-btn" onclick="switchTab(event, 'processing')">
                        Processing <span class="tab-badge">38</span>
                    </button>
                    <button class="tab-btn" onclick="switchTab(event, 'completed')">
                        Completed <span class="tab-badge">44</span>
                    </button>
                    <button class="tab-btn" onclick="switchTab(event, 'on-hold')">
                        On Hold <span class="tab-badge">0</span>
                    </button>
                </div>

                <!-- Pending Tab -->
                <div id="pending" class="tab-content active">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Due Date</th>
                                <th>Attachments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#001</td>
                                <td>Review Design Mockups</td>
                                <td>Website Redesign</td>
                                <td>
                                    <select class="status-select status-pending" onchange="updateStatus(this, 1)">
                                        <option value="pending" selected>Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="on-hold">On Hold</option>
                                    </select>
                                </td>
                                <td>High</td>
                                <td>2025-10-15</td>
                                <td>
                                    <span class="attachment-indicator">
                                        📎 3 files
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn view" onclick="showDetails(1)"
                                            title="View Details">👁️</button>
                                        <button class="icon-btn edit" title="Edit">✏️</button>
                                        <button class="icon-btn delete" title="Delete">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#002</td>
                                <td>Content Strategy Document</td>
                                <td>Marketing Campaign</td>
                                <td>
                                    <select class="status-select status-pending" onchange="updateStatus(this, 2)">
                                        <option value="pending" selected>Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="on-hold">On Hold</option>
                                    </select>
                                </td>
                                <td>Medium</td>
                                <td>2025-10-18</td>
                                <td>
                                    <span class="attachment-indicator">
                                        📎 1 file
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn view" onclick="showDetails(2)"
                                            title="View Details">👁️</button>
                                        <button class="icon-btn edit" title="Edit">✏️</button>
                                        <button class="icon-btn delete" title="Delete">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#003</td>
                                <td>User Testing Plan</td>
                                <td>Mobile App Development</td>
                                <td>
                                    <select class="status-select status-pending" onchange="updateStatus(this, 3)">
                                        <option value="pending" selected>Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="on-hold">On Hold</option>
                                    </select>
                                </td>
                                <td>Low</td>
                                <td>2025-10-20</td>
                                <td>-</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn view" onclick="showDetails(3)"
                                            title="View Details">👁️</button>
                                        <button class="icon-btn edit" title="Edit">✏️</button>
                                        <button class="icon-btn delete" title="Delete">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing 1-3 of 42 items
                        </div>
                        <div class="pagination">
                            <button class="pagination-btn" disabled>← Prev</button>
                            <button class="pagination-btn active">1</button>
                            <button class="pagination-btn">2</button>
                            <button class="pagination-btn">3</button>
                            <button class="pagination-btn dots">...</button>
                            <button class="pagination-btn">14</button>
                            <button class="pagination-btn">Next →</button>
                        </div>
                    </div>
                </div>

                <!-- Processing Tab -->
                <div id="processing" class="tab-content">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Due Date</th>
                                <th>Attachments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#004</td>
                                <td>API Integration Testing</td>
                                <td>Mobile App Development</td>
                                <td>
                                    <select class="status-select status-processing" onchange="updateStatus(this, 4)">
                                        <option value="pending">Pending</option>
                                        <option value="processing" selected>Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="on-hold">On Hold</option>
                                    </select>
                                </td>
                                <td>Urgent</td>
                                <td>2025-10-12</td>
                                <td>
                                    <span class="attachment-indicator">
                                        📎 5 files
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn view" onclick="showDetails(4)"
                                            title="View Details">👁️</button>
                                        <button class="icon-btn edit" title="Edit">✏️</button>
                                        <button class="icon-btn delete" title="Delete">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#005</td>
                                <td>Database Schema Review</td>
                                <td>Database Migration</td>
                                <td>
                                    <select class="status-select status-processing" onchange="updateStatus(this, 5)">
                                        <option value="pending">Pending</option>
                                        <option value="processing" selected>Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="on-hold">On Hold</option>
                                    </select>
                                </td>
                                <td>High</td>
                                <td>2025-10-14</td>
                                <td>
                                    <span class="attachment-indicator">
                                        📎 2 files
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn view" onclick="showDetails(5)"
                                            title="View Details">👁️</button>
                                        <button class="icon-btn edit" title="Edit">✏️</button>
                                        <button class="icon-btn delete" title="Delete">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing 1-2 of 38 items
                        </div>
                        <div class="pagination">
                            <button class="pagination-btn" disabled>← Prev</button>
                            <button class="pagination-btn active">1</button>
                            <button class="pagination-btn">2</button>
                            <button class="pagination-btn">3</button>
                            <button class="pagination-btn dots">...</button>
                            <button class="pagination-btn">13</button>
                            <button class="pagination-btn">Next →</button>
                        </div>
                    </div>
                </div>

                <!-- Completed Tab -->
                <div id="completed" class="tab-content">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Completed Date</th>
                                <th>Attachments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#006</td>
                                <td>Homepage Design</td>
                                <td>Website Redesign</td>
                                <td>
                                    <select class="status-select status-completed" onchange="updateStatus(this, 6)">
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed" selected>Completed</option>
                                        <option value="on-hold">On Hold</option>
                                    </select>
                                </td>
                                <td>High</td>
                                <td>2025-10-08</td>
                                <td>
                                    <span class="attachment-indicator">
                                        📎 8 files
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn view" onclick="showDetails(6)"
                                            title="View Details">👁️</button>
                                        <button class="icon-btn edit" title="Edit">✏️</button>
                                        <button class="icon-btn delete" title="Delete">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#007</td>
                                <td>SEO Optimization</td>
                                <td>Marketing Campaign</td>
                                <td>
                                    <select class="status-select status-completed" onchange="updateStatus(this, 7)">
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed" selected>Completed</option>
                                        <option value="on-hold">On Hold</option>
                                    </select>
                                </td>
                                <td>Medium</td>
                                <td>2025-10-05</td>
                                <td>-</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn view" onclick="showDetails(7)"
                                            title="View Details">👁️</button>
                                        <button class="icon-btn edit" title="Edit">✏️</button>
                                        <button class="icon-btn delete" title="Delete">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing 1-2 of 44 items
                        </div>
                        <div class="pagination">
                            <button class="pagination-btn" disabled>← Prev</button>
                            <button class="pagination-btn active">1</button>
                            <button class="pagination-btn">2</button>
                            <button class="pagination-btn">3</button>
                            <button class="pagination-btn dots">...</button>
                            <button class="pagination-btn">15</button>
                            <button class="pagination-btn">Next →</button>
                        </div>
                    </div>
                </div>

                <!-- On Hold Tab -->
                <div id="on-hold" class="tab-content">
                    <div class="empty-state">
                        <div class="empty-state-icon">⏸️</div>
                        <h3>No Items On Hold</h3>
                        <p>There are currently no items with "On Hold" status</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- DETAILS PAGE -->
        <div id="details" class="page">
            <div class="details-container">
                <div class="page-header">
                    <h1>Item Details</h1>
                    <button class="action-btn back" onclick="showPage('list')">
                        ← Back to List
                    </button>
                </div>

                <!-- Main Details Card -->
                <div class="detail-card">
                    <div class="detail-header">
                        <div class="detail-title">API Integration Testing</div>
                        <div class="detail-actions">
                            <button class="action-btn edit">
                                ✏️ Edit
                            </button>
                            <button class="action-btn delete">
                                🗑️ Delete
                            </button>
                        </div>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Item ID</div>
                            <div class="detail-value">#004</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span class="status-badge status-processing">Processing</span>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Project</div>
                            <div class="detail-value">Mobile App Development</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Priority</div>
                            <div class="detail-value">Urgent</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Assigned To</div>
                            <div class="detail-value">John Doe</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Due Date</div>
                            <div class="detail-value">October 12, 2025</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Created Date</div>
                            <div class="detail-value">October 1, 2025</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">Progress</div>
                            <div class="detail-value">65%</div>
                        </div>

                        <div class="detail-item full-width">
                            <div class="detail-label">Description</div>
                            <div class="detail-value">
                                Integration testing for the REST API endpoints including authentication, user
                                management, and data synchronization features. Need to verify response times, error
                                handling, and data integrity across all endpoints.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attachments Card -->
                <div class="detail-card">
                    <div class="attachments-section">
                        <h3>Attached Files (5)</h3>

                        <div class="attachment-grid">
                            <!-- Image Attachment -->
                            <div class="attachment-card">
                                <div class="attachment-preview">
                                    <img src="https://via.placeholder.com/250x150/667eea/ffffff?text=Screenshot"
                                        alt="Screenshot">
                                </div>
                                <div class="attachment-info">
                                    <div class="attachment-name">api-screenshot.png</div>
                                    <div class="attachment-size">1.2 MB</div>
                                </div>
                                <div class="attachment-actions">
                                    <button class="download-btn">⬇️ Download</button>
                                    <button class="delete-attach-btn">🗑️</button>
                                </div>
                            </div>

                            <!-- PDF Attachment -->
                            <div class="attachment-card">
                                <div class="attachment-preview">
                                    📄
                                </div>
                                <div class="attachment-info">
                                    <div class="attachment-name">api-documentation.pdf</div>
                                    <div class="attachment-size">3.5 MB</div>
                                </div>
                                <div class="attachment-actions">
                                    <button class="download-btn">⬇️ Download</button>
                                    <button class="delete-attach-btn">🗑️</button>
                                </div>
                            </div>

                            <!-- Excel Attachment -->
                            <div class="attachment-card">
                                <div class="attachment-preview">
                                    📊
                                </div>
                                <div class="attachment-info">
                                    <div class="attachment-name">test-results.xlsx</div>
                                    <div class="attachment-size">856 KB</div>
                                </div>
                                <div class="attachment-actions">
                                    <button class="download-btn">⬇️ Download</button>
                                    <button class="delete-attach-btn">🗑️</button>
                                </div>
                            </div>

                            <!-- Word Document -->
                            <div class="attachment-card">
                                <div class="attachment-preview">
                                    📝
                                </div>
                                <div class="attachment-info">
                                    <div class="attachment-name">testing-checklist.docx</div>
                                    <div class="attachment-size">245 KB</div>
                                </div>
                                <div class="attachment-actions">
                                    <button class="download-btn">⬇️ Download</button>
                                    <button class="delete-attach-btn">🗑️</button>
                                </div>
                            </div>

                            <!-- Another Image -->
                            <div class="attachment-card">
                                <div class="attachment-preview">
                                    <img src="https://via.placeholder.com/250x150/764ba2/ffffff?text=Diagram"
                                        alt="Diagram">
                                </div>
                                <div class="attachment-info">
                                    <div class="attachment-name">architecture-diagram.jpg</div>
                                    <div class="attachment-size">2.1 MB</div>
                                </div>
                                <div class="attachment-actions">
                                    <button class="download-btn">⬇️ Download</button>
                                    <button class="delete-attach-btn">🗑️</button>
                                </div>
                            </div>
                        </div>

                        <!-- Upload More Files Section -->
                        <div style="margin-top: 25px;">
                            <div class="file-upload-area" onclick="document.getElementById('detailsFileInput').click()">
                                <input type="file" id="detailsFileInput" multiple>
                                <div class="file-icon">➕</div>
                                <div>Add More Files</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="{{asset('js/main.js')}}"></script>
</body>

</html>