        @extends('layouts')

        @section('content')
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
        @endsection
