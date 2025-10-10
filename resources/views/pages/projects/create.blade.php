@extends('layouts')

@section('content')
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
@endsection
