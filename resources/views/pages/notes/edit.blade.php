@extends('layouts')

@section('content')
    <div id="edit-note" class="page">
        <div class="page-header">
            <h1>Edit Note</h1>
        </div>

        @include('design.includes.alert')

        <div class="form-card">
            <form action="{{ route('notes.update', $note->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Select Project *</label>
                        <select name="project_id" required>
                            <option value="">-- Choose Project --</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id', $note->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Note Title *</label>
                        <input type="text" name="title" placeholder="Enter note title"
                            value="{{ old('title', $note->title) }}" required>
                    </div>

                    <div class="form-group full-width">
                        <label>Content</label>
                        <textarea name="content" rows="10" placeholder="Enter note content...">{{ old('content', $note->content) }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>
                            <input type="checkbox" name="is_private" value="1"
                                {{ old('is_private', $note->is_private) ? 'checked' : '' }}>
                            Make this note private
                        </label>
                    </div>

                    <!-- Existing Attachments -->
                    @if ($note->attachments->count() > 0)
                        <div class="form-group full-width">
                            <label>Existing Attachments</label>
                            <div class="uploaded-files-list">
                                @foreach ($note->attachments as $attachment)
                                    <div class="file-item" id="attachment-{{ $attachment->id }}">
                                        <div class="file-item-content">
                                            <span class="file-item-icon">
                                                @if (str_starts_with($attachment->mime_type, 'image/'))
                                                    🖼️
                                                @elseif(str_contains($attachment->mime_type, 'pdf'))
                                                    📄
                                                @elseif(str_contains($attachment->mime_type, 'word'))
                                                    📝
                                                @elseif(str_contains($attachment->mime_type, 'sheet'))
                                                    📊
                                                @else
                                                    📎
                                                @endif
                                            </span>
                                            <div class="file-item-info">
                                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                                    class="file-item-name file-item-link">
                                                    {{ $attachment->original_filename }}
                                                </a>
                                                <div class="file-item-size">{{ $attachment->formatted_size }}</div>
                                            </div>
                                        </div>
                                        <button type="button" class="file-remove-btn"
                                            onclick="deleteAttachment({{ $attachment->id }})" title="Delete attachment">
                                            ✕
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Add More Attachments -->
                    <div class="form-group full-width">
                        <label>Add More Attachments</label>
                        <div class="file-upload-area" onclick="document.getElementById('newFileInput').click()">
                            <input type="file" id="newFileInput" multiple style="display: none;">
                            <div class="file-icon">📎</div>
                            <div>Click to upload or drag and drop</div>
                            <div style="font-size: 12px; color: #a0aec0; margin-top: 5px;">
                                PDF, DOC, XLS, Images (Max 10MB each)
                            </div>
                        </div>
                        <div class="uploaded-files-list" id="newFilesList">
                            <!-- New files will appear here -->
                        </div>
                        @if (count($note->attachments) > 0 || true)
                            <button type="button" class="upload-new-files-btn" id="uploadBtn"
                                style="display: none; margin-top: 10px;">
                                Upload Selected Files
                            </button>
                        @endif
                    </div>
                </div>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <button type="submit" class="submit-btn" style="margin-top: 0 !important;">Update Note</button>
                    <a href="{{ route('notes.show', $note->id) }}" class="btn btn-secondary"
                        style="margin-top: 0 !important;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function displayNewFiles(files) {
            const filesList = document.getElementById('newFilesList');
            const uploadBtn = document.getElementById('uploadBtn');
            filesList.innerHTML = '';

            if (!files || files.length === 0) {
                uploadBtn.style.display = 'none';
                return;
            }

            uploadBtn.style.display = 'block';

            Array.from(files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';

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
                    <button type="button" class="file-remove-btn" onclick="removeNewFile(${index})" title="Remove file">
                        ✕
                    </button>
                `;

                filesList.appendChild(fileItem);
            });
        }

        function removeNewFile(index) {
            const fileInput = document.getElementById('newFileInput');
            const dataTransfer = new DataTransfer();

            const files = Array.from(fileInput.files);
            files.splice(index, 1);

            files.forEach(file => {
                dataTransfer.items.add(file);
            });

            fileInput.files = dataTransfer.files;
            displayNewFiles(fileInput.files);
        }

        function uploadNewFiles() {
            const fileInput = document.getElementById('newFileInput');

            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Please select files to upload');
                return;
            }

            const uploadBtn = document.getElementById('uploadBtn');
            const originalText = uploadBtn.textContent;
            uploadBtn.disabled = true;
            uploadBtn.textContent = 'Uploading...';

            const formData = new FormData();
            Array.from(fileInput.files).forEach(file => {
                formData.append('files[]', file);
            });

            fetch('{{ route('notes.attachments.upload', $note->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Upload failed: ' + (data.message || 'Unknown error'));
                        uploadBtn.disabled = false;
                        uploadBtn.textContent = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Upload failed. Please try again.');
                    uploadBtn.disabled = false;
                    uploadBtn.textContent = originalText;
                });
        }

        function deleteAttachment(attachmentId) {
            if (!confirm('Are you sure you want to delete this attachment?')) {
                return;
            }

            fetch(`/notes/attachments/${attachmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`attachment-${attachmentId}`).remove();
                    } else {
                        alert(data.message || 'Delete failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Delete failed. Please try again.');
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('newFileInput');
            const uploadBtn = document.getElementById('uploadBtn');

            // Upload button click handler
            uploadBtn.addEventListener('click', uploadNewFiles);

            fileInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                const invalidFiles = files.filter(file => file.size > 300 * 1024 * 1024);

                if (invalidFiles.length > 0) {
                    alert(
                        `The following files exceed 300MB limit:\n${invalidFiles.map(f => f.name).join('\n')}`);
                    fileInput.value = '';
                    return;
                }

                displayNewFiles(e.target.files);
            });

            // Drag and drop
            const uploadArea = document.querySelector('.file-upload-area');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

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
                const droppedFiles = Array.from(e.dataTransfer.files);
                const invalidFiles = droppedFiles.filter(file => file.size > 300 * 1024 * 1024);

                if (invalidFiles.length > 0) {
                    alert(
                        `The following files exceed 300MB limit:\n${invalidFiles.map(f => f.name).join('\n')}`);
                    return;
                }

                const existingFiles = Array.from(fileInput.files);
                const dataTransfer = new DataTransfer();

                [...existingFiles, ...droppedFiles].forEach(file => {
                    dataTransfer.items.add(file);
                });

                fileInput.files = dataTransfer.files;
                displayNewFiles(fileInput.files);
            });
        });
    </script>

    <style>
        /* Fixed button container spacing */
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            /* Fixed spacing */
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

        .upload-new-files-btn {
            padding: 10px 20px;
            background: #48bb78;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
            width: 100%;
        }

        .upload-new-files-btn:hover:not(:disabled) {
            background: #38a169;
        }

        .upload-new-files-btn:disabled {
            background: #a0aec0;
            cursor: not-allowed;
        }

        /* File Upload Area - Same as create page */
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

        .file-item-link {
            color: #4299e1;
            text-decoration: none;
        }

        .file-item-link:hover {
            color: #2b6cb0;
            text-decoration: underline;
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
