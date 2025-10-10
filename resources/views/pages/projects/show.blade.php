        @extends('layouts')

        @section('content')
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
        @endsection
