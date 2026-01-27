@extends('layouts')

@section('content')
    <!-- DETAILS PAGE -->
    <div id="details" class="page active">
        <div class="details-container">
            <div class="page-header">
                <h1>Item Details</h1>
                <button class="action-btn back" onclick="window.location.href='{{ route('projectmng.list') }}'">
                    ← Back to List
                </button>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Main Details Card -->
            <div class="detail-card">
                <div class="detail-header">
                    <div class="detail-title">
                        {{ $item->title }}
                        @if ($item->is_private)
                            <span style="color: #f56565; font-size: 14px; margin-left: 10px;">🔒 Private</span>
                        @endif
                    </div>
                    <div class="detail-actions">
                        @if ($item->created_by == auth()->id())
                            <button class="action-btn edit" onclick="alert('Edit functionality coming soon')">
                                ✏️ Edit
                            </button>
                            <button class="action-btn delete" onclick="deleteItem({{ $item->id }})">
                                🗑️ Delete
                            </button>
                        @endif
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Item ID</div>
                        <div class="detail-value">#{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="status-badge status-{{ $item->status }}">{{ ucfirst($item->status) }}</span>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Project</div>
                        <div class="detail-value">{{ $item->project->name }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Priority</div>
                        <div class="detail-value">{{ ucfirst($item->priority) }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Created By</div>
                        <div class="detail-value">{{ $item->creator->name }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Assigned To</div>
                        <div class="detail-value">
                            {{ $item->assignedUser ? $item->assignedUser->name : 'Not Assigned' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Due Date</div>
                        <div class="detail-value">
                            {{ $item->due_date ? $item->due_date->format('F d, Y') : 'No due date' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Created Date</div>
                        <div class="detail-value">{{ $item->created_at->format('F d, Y') }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value">{{ $item->updated_at->format('F d, Y h:i A') }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Progress</div>
                        <div class="detail-value">{{ $item->progress }}%</div>
                    </div>

                    @if ($item->completed_at)
                        <div class="detail-item">
                            <div class="detail-label">Completed Date</div>
                            <div class="detail-value">{{ $item->completed_at->format('F d, Y h:i A') }}</div>
                        </div>
                    @endif

                    <div class="detail-item full-width">
                        <div class="detail-label">Description</div>
                        <div class="detail-value">
                            {{ $item->description ?: 'No description provided' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attachments Card -->
            <div class="detail-card">
                <div class="attachments-section">
                    <h3>Attached Files ({{ $item->attachments->count() }})</h3>

                    @if ($item->attachments->count() > 0)
                        <div class="attachment-grid">
                            @foreach ($item->attachments as $attachment)
                                <div class="attachment-card">
                                    <div class="attachment-preview">
                                        @if (str_starts_with($attachment->mime_type, 'image/'))
                                            <img src="{{ asset('storage/' . $attachment->file_path) }}"
                                                alt="{{ $attachment->original_filename }}"
                                                style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">
                                        @elseif(str_contains($attachment->mime_type, 'pdf'))
                                            <div style="font-size: 48px;">📄</div>
                                        @elseif(str_contains($attachment->mime_type, 'spreadsheet') ||
                                                str_contains($attachment->original_filename, '.xlsx') ||
                                                str_contains($attachment->original_filename, '.xls'))
                                            <div style="font-size: 48px;">📊</div>
                                        @elseif(str_contains($attachment->mime_type, 'word') ||
                                                str_contains($attachment->original_filename, '.docx') ||
                                                str_contains($attachment->original_filename, '.doc'))
                                            <div style="font-size: 48px;">📝</div>
                                        @else
                                            <div style="font-size: 48px;">📎</div>
                                        @endif
                                    </div>
                                    <div class="attachment-info">
                                        <div class="attachment-name" title="{{ $attachment->original_filename }}">
                                            {{ Str::limit($attachment->original_filename, 30) }}
                                        </div>
                                        <div class="attachment-size">{{ $attachment->formatted_size }}</div>
                                        <div style="font-size: 12px; color: #718096; margin-top: 4px;">
                                            Uploaded by {{ $attachment->uploader->name }}
                                        </div>
                                    </div>
                                    <div class="attachment-actions">
                                        <a href="{{ asset('storage/' . $attachment->file_path) }}"
                                            download="{{ $attachment->original_filename }}" class="download-btn">⬇️
                                            Download</a>
                                        <button class="delete-attach-btn"
                                            onclick="deleteAttachment({{ $attachment->id }})">🗑️</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state" style="margin: 20px 0;">
                            <div class="empty-state-icon">📎</div>
                            <p>No attachments yet</p>
                        </div>
                    @endif

                    <!-- Upload More Files Section -->
                    <div style="margin-top: 25px;">
                        <div class="file-upload-area" onclick="document.getElementById('detailsFileInput').click()">
                            <input type="file" id="detailsFileInput" multiple style="display: none;">
                            <div class="file-icon">➕</div>
                            <div>Add More Files</div>
                            <div style="font-size: 12px; color: #a0aec0; margin-top: 5px;">
                                Click to upload (Max 10MB per file)
                            </div>
                        </div>
                    </div>
                </div>
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

        .attachment-name {
            word-break: break-word;
            font-weight: 500;
        }

        .download-btn {
            text-decoration: none;
            background: #4299e1;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            display: inline-block;
            transition: background 0.2s;
        }

        .download-btn:hover {
            background: #3182ce;
        }

        .delete-attach-btn {
            background: #f56565;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s;
        }

        .delete-attach-btn:hover {
            background: #e53e3e;
        }

        .attachment-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }
    </style>
@endsection
