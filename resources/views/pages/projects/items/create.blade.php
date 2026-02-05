@extends('layouts')

@section('content')
    <!-- CREATE PAGE -->
    <div id="create" class="page">
        <div class="page-header">
            <h1>Create New Assignment/Note</h1>
        </div>

        @include('design.includes.alert')

        <div class="form-card">
            <form id="createForm" action="{{ route('projectmng.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Select Project *</label>
                        <select name="project_id" required>
                            <option value="">-- Choose Project --</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Item Title *</label>
                        <input type="text" name="title" placeholder="Enter item title" value="{{ old('title') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="on-hold" {{ old('status') == 'on-hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Priority</label>
                        <select name="priority">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium
                            </option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}">
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_private" id="isPrivateCheckbox" value="1"
                                {{ old('is_private') ? 'checked' : '' }} onchange="toggleAssignment()">
                            Make this item private
                        </label>
                    </div>

                    <div class="form-group" id="assignmentGroup">
                        <label>Assigned To</label>
                        <select name="assigned_to" id="assignedToSelect">
                            <option value="">-- Not Assigned --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Progress (%)</label>
                        <input type="number" name="progress" min="0" max="100"
                            value="{{ old('progress', 0) }}">
                    </div>

                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description" placeholder="Enter detailed description...">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Attach Files</label>
                        <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                            <input type="file" id="fileInput" multiple style="display: none;">
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

    <script>
        // Store selected files
        let selectedFiles = [];

        function toggleAssignment() {
            const isPrivate = document.getElementById('isPrivateCheckbox').checked;
            const assignmentGroup = document.getElementById('assignmentGroup');
            const assignedToSelect = document.getElementById('assignedToSelect');

            if (isPrivate) {
                assignmentGroup.style.opacity = '0.5';
                assignedToSelect.disabled = true;
                assignedToSelect.value = '';
            } else {
                assignmentGroup.style.opacity = '1';
                assignedToSelect.disabled = false;
            }
        }

        // Update the file input with current selected files
        function updateFileInput() {
            const fileInput = document.getElementById('fileInput');
            const dataTransfer = new DataTransfer();

            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });

            fileInput.files = dataTransfer.files;
        }

        // Remove file from selection
        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileInput();
            displayFiles();
        }

        // Display selected files
        function displayFiles() {
            const filesList = document.getElementById('uploadedFilesList');
            filesList.innerHTML = '';

            if (selectedFiles.length === 0) {
                return;
            }

            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';

                // Determine file icon based on type
                let fileIcon = '📎';
                if (file.type.startsWith('image/')) {
                    fileIcon = '🖼️';
                } else if (file.type.includes('pdf')) {
                    fileIcon = '📄';
                } else if (file.type.includes('word') || file.name.endsWith('.docx') || file.name.endsWith(
                    '.doc')) {
                    fileIcon = '📝';
                } else if (file.type.includes('sheet') || file.name.endsWith('.xlsx') || file.name.endsWith(
                    '.xls')) {
                    fileIcon = '📊';
                }

                fileItem.innerHTML = `
                    <div class="file-item-content">
                        <span class="file-item-icon">${fileIcon}</span>
                        <div class="file-item-info">
                            <div class="file-item-name">${file.name}</div>
                            <div class="file-item-size">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                        </div>
                    </div>
                    <button type="button" class="file-remove-btn" onclick="removeFile(${index})" title="Remove file">
                        ✕
                    </button>
                `;

                filesList.appendChild(fileItem);
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleAssignment();

            // File upload handling
            const fileInput = document.getElementById('fileInput');

            fileInput.addEventListener('change', function(e) {
                const newFiles = Array.from(e.target.files);

                // Check file sizes
                const invalidFiles = newFiles.filter(file => file.size > 10 * 1024 * 1024);
                if (invalidFiles.length > 0) {
                    alert(
                        `The following files exceed 10MB limit:\n${invalidFiles.map(f => f.name).join('\n')}`);
                    return;
                }

                // Add new files to selection (avoiding duplicates by name)
                newFiles.forEach(newFile => {
                    const exists = selectedFiles.some(f => f.name === newFile.name && f.size ===
                        newFile.size);
                    if (!exists) {
                        selectedFiles.push(newFile);
                    }
                });

                updateFileInput();
                displayFiles();
            });

            // Drag and drop support
            const uploadArea = document.querySelector('.file-upload-area');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => {
                    uploadArea.classList.add('drag-over');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => {
                    uploadArea.classList.remove('drag-over');
                });
            });

            uploadArea.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = Array.from(dt.files);

                // Check file sizes
                const invalidFiles = files.filter(file => file.size > 10 * 1024 * 1024);
                if (invalidFiles.length > 0) {
                    alert(
                        `The following files exceed 10MB limit:\n${invalidFiles.map(f => f.name).join('\n')}`);
                    return;
                }

                // Add files
                files.forEach(file => {
                    const exists = selectedFiles.some(f => f.name === file.name && f.size === file
                        .size);
                    if (!exists) {
                        selectedFiles.push(file);
                    }
                });

                updateFileInput();
                displayFiles();
            });

            // Form submission validation
            document.getElementById('createForm').addEventListener('submit', function(e) {
                // The files are already in the input, so form will submit normally
                console.log('Submitting with', selectedFiles.length, 'files');
            });
        });
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

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .file-upload-area {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-area.drag-over {
            background: #ebf8ff;
            border-color: #4299e1;
            transform: scale(1.02);
        }

        .uploaded-files-list {
            margin-top: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 15px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .file-item:hover {
            background: #edf2f7;
            border-color: #cbd5e0;
        }

        .file-item-content {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .file-item-icon {
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        .file-item-info {
            flex: 1;
            min-width: 0;
        }

        .file-item-name {
            font-weight: 500;
            color: #2d3748;
            word-break: break-word;
            margin-bottom: 2px;
        }

        .file-item-size {
            font-size: 0.8125rem;
            color: #718096;
        }

        .file-remove-btn {
            background: #fed7d7;
            color: #c53030;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.2s;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-remove-btn:hover {
            background: #fc8181;
            color: white;
            transform: scale(1.1);
        }

        .file-remove-btn:active {
            transform: scale(0.95);
        }

        /* Empty state for file list */
        .uploaded-files-list:empty::after {
            content: '';
            display: none;
        }
    </style>
@endsection
