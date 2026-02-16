@extends('layouts')

@section('content')
    <div id="create-note" class="page">
        <div class="page-header">
            <h1>Create New Note</h1>
        </div>

        @include('design.includes.alert')

        <div class="form-card">
            <form id="createNoteForm" action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
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

                    <div class="form-group full-width">
                        <label>Note Title *</label>
                        <input type="text" name="title" placeholder="Enter note title" value="{{ old('title') }}"
                            required>
                    </div>

                    <div class="form-group full-width">
                        <label>Content</label>
                        <textarea name="content" rows="10" placeholder="Enter note content...">{{ old('content') }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>
                            <input type="checkbox" name="is_private" value="1"
                                {{ old('is_private') ? 'checked' : '' }}>
                            Make this note private
                        </label>
                    </div>

                    <div class="form-group full-width">
                        <label>Attach Files</label>
                        <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                            <input type="file" id="fileInput" name="attachments[]" multiple style="display: none;">
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

                <div style="display: flex; gap: 10px; align-items: center;">
                    <button type="submit" class="submit-btn" style="margin-top: 0 !important;">Create Note</button>
                    <a href="{{ route('notes.index') }}" class="btn btn-secondary"
                        style="margin-top: 0 !important;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function displayFiles(files) {
            const filesList = document.getElementById('uploadedFilesList');
            filesList.innerHTML = '';

            if (!files || files.length === 0) {
                return;
            }

            Array.from(files).forEach((file, index) => {
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

        function removeFile(index) {
            const fileInput = document.getElementById('fileInput');
            const dataTransfer = new DataTransfer();

            const files = Array.from(fileInput.files);
            files.splice(index, 1);

            files.forEach(file => {
                dataTransfer.items.add(file);
            });

            fileInput.files = dataTransfer.files;
            displayFiles(fileInput.files);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fileInput');

            fileInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);

                // Check file sizes
                const invalidFiles = files.filter(file => file.size > 300 * 1024 * 1024);
                if (invalidFiles.length > 0) {
                    alert(
                        `The following files exceed 300MB limit:\n${invalidFiles.map(f => f.name).join('\n')}`);
                    fileInput.value = '';
                    return;
                }

                displayFiles(e.target.files);
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
                const droppedFiles = Array.from(dt.files);

                // Check file sizes
                const invalidFiles = droppedFiles.filter(file => file.size > 300 * 1024 * 1024);
                if (invalidFiles.length > 0) {
                    alert(
                        `The following files exceed 300MB limit:\n${invalidFiles.map(f => f.name).join('\n')}`);
                    return;
                }

                // Get existing files
                const existingFiles = Array.from(fileInput.files);

                // Combine existing and new files
                const dataTransfer = new DataTransfer();
                [...existingFiles, ...droppedFiles].forEach(file => {
                    dataTransfer.items.add(file);
                });

                fileInput.files = dataTransfer.files;
                displayFiles(fileInput.files);
            });

            document.getElementById('createNoteForm').addEventListener('submit', function(e) {
                console.log('Submitting with', fileInput.files.length, 'files');
            });
        });
    </script>

    <style>
        /* Fixed button container spacing */
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            align-items: center;
        }

        .btn-secondary {
            padding: 12px 24px;
            background: #e2e8f0;
            color: #2d3748;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        .file-upload-area {
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .file-upload-area:hover {
            border-color: #4299e1;
            background: #ebf8ff;
        }

        .file-upload-area.drag-over {
            background: #ebf8ff;
            border-color: #4299e1;
            transform: scale(1.02);
        }

        .file-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
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
    </style>
@endsection
