@extends('layouts')

@section('content')
    <div class="assignment-details-page">
        <div class="assignment-container">
            <!-- Back Navigation -->
            <a href="{{ route('projectmng.list') }}" class="back-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>

            @if (session('success'))
                <div class="success-alert">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div id="statusAlert" class="status-alert" style="display:none;"></div>

            <!-- Main Content Card -->
            <article class="assignment-card">
                <!-- Header Section -->
                <header class="assignment-header">
                    <div class="header-main">
                        <h1 class="assignment-title">{{ $item->title }}</h1>
                        <div class="header-badges">
                            <span id="statusBadge" class="status-badge status-{{ $item->status }}">
                                {{ ucfirst(str_replace('-', ' ', $item->status)) }}
                            </span>
                            @if ($item->is_private)
                                <span class="private-badge">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Private
                                </span>
                            @endif

                            <div class="status-change">
                                <label for="statusSelect" class="status-change-label">Change status</label>
                                <select id="statusSelect" class="status-select" data-item-id="{{ $item->id }}">
                                    <option value="pending" @selected($item->status === 'pending')>Pending</option>
                                    <option value="processing" @selected($item->status === 'processing')>Processing</option>
                                    <option value="completed" @selected($item->status === 'completed')>Completed</option>
                                    <option value="on-hold" @selected($item->status === 'on-hold')>On Hold</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Info Bar -->
                    <div class="quick-info">
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            {{ $item->project->name }}
                        </div>
                        <span class="divider">•</span>
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Created by {{ $item->creator->name }}
                        </div>
                        <span class="divider">•</span>
                        <div class="info-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $item->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </header>

                <!-- Description Section -->
                <section class="description-section">
                    <h2 class="section-title">Description</h2>
                    <div class="description-content">
                        {{ $item->description ?: 'No description provided' }}
                    </div>
                </section>

                <!-- Key Details Grid -->
                <section class="details-section">
                    <h2 class="section-title">Assignment Details</h2>
                    <div class="details-grid">
                        <div class="detail-box">
                            <div class="detail-icon">👤</div>
                            <div class="detail-content">
                                <div class="detail-label">Assigned To</div>
                                <div class="detail-value">
                                    {{ $item->assignedUser ? $item->assignedUser->name : 'Not Assigned' }}
                                </div>
                            </div>
                        </div>

                        <div class="detail-box">
                            <div class="detail-icon priority-{{ $item->priority }}">
                                @if ($item->priority === 'high' || $item->priority === 'urgent')
                                    🔴
                                @elseif($item->priority === 'medium')
                                    🟡
                                @else
                                    🟢
                                @endif
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Priority</div>
                                <div class="detail-value">{{ ucfirst($item->priority) }}</div>
                            </div>
                        </div>

                        <div class="detail-box">
                            <div class="detail-icon">📅</div>
                            <div class="detail-content">
                                <div class="detail-label">Due Date</div>
                                <div class="detail-value">
                                    {{ $item->due_date ? $item->due_date->format('F d, Y') : 'No due date' }}
                                </div>
                            </div>
                        </div>

                        <div class="detail-box">
                            <div class="detail-icon">📊</div>
                            <div class="detail-content">
                                <div class="detail-label">Progress</div>
                                <div class="progress-container">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $item->progress }}%"></div>
                                    </div>
                                    <span class="progress-text">{{ $item->progress }}%</span>
                                </div>
                            </div>
                        </div>

                        @if ($item->completed_at)
                            <div class="detail-box">
                                <div class="detail-icon">✅</div>
                                <div class="detail-content">
                                    <div class="detail-label">Completed</div>
                                    <div class="detail-value">{{ $item->completed_at->format('M d, Y, h:i A') }}</div>
                                </div>
                            </div>
                        @endif

                        <div class="detail-box">
                            <div class="detail-icon">🕐</div>
                            <div class="detail-content">
                                <div class="detail-label">Last Updated</div>
                                <div class="detail-value">{{ $item->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Attachments Section -->
                <section class="attachments-section">
                    <div class="section-header">
                        <h2 class="section-title">Attachments</h2>
                        <span class="attachment-count">{{ $item->attachments->count() }}
                            {{ Str::plural('file', $item->attachments->count()) }}</span>
                    </div>

                    @if ($item->attachments->count() > 0)
                        <div class="attachments-grid">
                            @foreach ($item->attachments as $attachment)
                                <div class="attachment-item">
                                    <div class="attachment-preview">
                                        @if (str_starts_with($attachment->mime_type, 'image/'))
                                            <img src="{{ asset('storage/' . $attachment->file_path) }}"
                                                alt="{{ $attachment->original_filename }}" class="attachment-image">
                                        @elseif(str_contains($attachment->mime_type, 'pdf'))
                                            <div class="file-icon">📄</div>
                                        @elseif(str_contains($attachment->mime_type, 'spreadsheet') ||
                                                str_contains($attachment->original_filename, '.xlsx') ||
                                                str_contains($attachment->original_filename, '.xls'))
                                            <div class="file-icon">📊</div>
                                        @elseif(str_contains($attachment->mime_type, 'word') ||
                                                str_contains($attachment->original_filename, '.docx') ||
                                                str_contains($attachment->original_filename, '.doc'))
                                            <div class="file-icon">📝</div>
                                        @else
                                            <div class="file-icon">📎</div>
                                        @endif
                                    </div>
                                    <div class="attachment-details">
                                        <div class="attachment-name" title="{{ $attachment->original_filename }}">
                                            {{ Str::limit($attachment->original_filename, 35) }}
                                        </div>
                                        <div class="attachment-meta">
                                            <span>{{ $attachment->formatted_size }}</span>
                                            <span class="divider">•</span>
                                            <span>{{ $attachment->uploader->name }}</span>
                                        </div>
                                        <div class="attachment-actions">
                                            <a href="{{ asset('storage/' . $attachment->file_path) }}"
                                                download="{{ $attachment->original_filename }}"
                                                class="action-link download">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Download
                                            </a>
                                            <button class="action-link delete"
                                                onclick="deleteAttachment({{ $attachment->id }})">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">📎</div>
                            <p class="empty-text">No attachments yet</p>
                        </div>
                    @endif

                    <!-- Upload More Files -->
                    <div class="upload-area" onclick="document.getElementById('detailsFileInput').click()">
                        <input type="file" id="detailsFileInput" multiple style="display: none;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <div class="upload-text">Add More Files</div>
                        <div class="upload-hint">Click to upload (Max 10MB per file)</div>
                    </div>
                </section>

                <!-- Action Buttons -->
                @if ($item->created_by == auth()->id())
                    <footer class="assignment-footer">
                        <div class="footer-actions">
                            <form action="{{ route('projectmng.edit', $item->id) }}" method="get"
                                style="display: inline;">
                                <button type="submit" class="btn btn-edit">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Assignment
                                </button>
                            </form>
                            <button class="btn btn-delete" onclick="deleteItem({{ $item->id }})">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete Assignment
                            </button>
                        </div>
                    </footer>
                @endif
            </article>
        </div>
    </div>

    <style>
        * {
            box-sizing: border-box;
        }

        .assignment-details-page {
            min-height: 100vh;
            background: #f4f5f7;
            padding: 2rem 1rem;
        }

        .assignment-container {
            max-width: 920px;
            margin: 0 auto;
        }

        /* Back Link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1rem;
            padding: 0.5rem 1rem;
            background: #ffffff;
            border: 1px solid #e2e4e9;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        .back-link:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .back-link svg {
            width: 18px;
            height: 18px;
        }

        /* Success / Status Alerts */
        .success-alert,
        .status-alert {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: #edf9f0;
            color: #1e6b34;
            padding: 0.875rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid #2f9e50;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .status-alert.error {
            background: #fdecec;
            color: #9b2226;
            border-left-color: #d64545;
        }

        .success-alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Main Card */
        .assignment-card {
            background: white;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.06);
            overflow: hidden;
        }

        /* Header */
        .assignment-header {
            padding: 1.75rem 2rem 1.5rem;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }

        .header-main {
            margin-bottom: 1rem;
        }

        .assignment-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 0.875rem 0;
            line-height: 1.35;
        }

        .header-badges {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            flex-wrap: wrap;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.875rem;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-on-hold {
            background: #fee2e2;
            color: #991b1b;
        }

        .private-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #f3f4f6;
            color: #4b5563;
            padding: 0.35rem 0.875rem;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .private-badge svg {
            width: 13px;
            height: 13px;
        }

        /* Inline status changer */
        .status-change {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: auto;
        }

        .status-change-label {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }

        .status-select {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #374151;
            padding: 0.4rem 1.75rem 0.4rem 0.75rem;
            border-radius: 7px;
            border: 1px solid #d1d5db;
            background-color: #fff;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3e%3cpath d='M6 9l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 14px;
            transition: border-color 0.2s;
        }

        .status-select:hover {
            border-color: #9ca3af;
        }

        .status-select:disabled {
            opacity: 0.6;
            cursor: wait;
        }

        .quick-info {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.625rem;
            font-size: 0.875rem;
            color: #4b5563;
            line-height: 1.6;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .info-item svg {
            width: 15px;
            height: 15px;
            opacity: 0.7;
        }

        .divider {
            color: #cbd5e0;
        }

        /* Sections */
        .description-section,
        .details-section,
        .attachments-section {
            padding: 1.75rem 2rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-title {
            font-size: 1.0625rem;
            font-weight: 600;
            color: #111827;
            margin: 0 0 1.25rem 0;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .attachment-count {
            background: #f3f4f6;
            color: #6b7280;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        /* Description */
        .description-content {
            font-size: 1rem;
            line-height: 1.75;
            color: #374151;
            white-space: pre-line;
            word-wrap: break-word;
        }

        /* Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1rem;
        }

        .detail-box {
            display: flex;
            gap: 0.875rem;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }

        .detail-box:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .detail-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .detail-content {
            flex: 1;
            min-width: 0;
        }

        .detail-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 0.375rem;
        }

        .detail-value {
            font-size: 0.9375rem;
            color: #1f2937;
            font-weight: 500;
            line-height: 1.5;
        }

        /* Progress Bar */
        .progress-container {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .progress-bar {
            flex: 1;
            height: 7px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #4f46e5;
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .progress-text {
            font-size: 0.875rem;
            font-weight: 600;
            color: #4f46e5;
        }

        /* Attachments */
        .attachments-grid {
            display: grid;
            gap: 0.875rem;
        }

        .attachment-item {
            display: flex;
            gap: 0.875rem;
            padding: 0.875rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .attachment-item:hover {
            background: #f3f4f6;
            border-color: #cbd5e0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .attachment-preview {
            width: 70px;
            height: 70px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .attachment-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .file-icon {
            font-size: 2.25rem;
        }

        .attachment-details {
            flex: 1;
            min-width: 0;
        }

        .attachment-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.375rem;
            word-break: break-word;
            font-size: 0.9375rem;
            line-height: 1.4;
        }

        .attachment-meta {
            font-size: 0.8125rem;
            color: #6b7280;
            margin-bottom: 0.625rem;
        }

        .attachment-actions {
            display: flex;
            gap: 0.875rem;
        }

        .action-link {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 500;
            text-decoration: none;
            padding: 0.375rem 0.625rem;
            border-radius: 5px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .action-link svg {
            width: 13px;
            height: 13px;
        }

        .action-link.download {
            color: #2563eb;
            background: #eff6ff;
        }

        .action-link.download:hover {
            background: #2563eb;
            color: white;
        }

        .action-link.delete {
            color: #dc2626;
            background: #fef2f2;
        }

        .action-link.delete:hover {
            background: #dc2626;
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
        }

        .empty-icon {
            font-size: 3.5rem;
            margin-bottom: 0.75rem;
            opacity: 0.4;
        }

        .empty-text {
            color: #9ca3af;
            font-size: 0.9375rem;
        }

        /* Upload Area */
        .upload-area {
            margin-top: 1.25rem;
            padding: 1.5rem;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .upload-area:hover {
            border-color: #9ca3af;
            background: #f9fafb;
        }

        .upload-area svg {
            width: 36px;
            height: 36px;
            color: #9ca3af;
            margin: 0 auto 0.625rem;
        }

        .upload-text {
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 0.25rem;
            font-size: 0.9375rem;
        }

        .upload-hint {
            font-size: 0.8125rem;
            color: #9ca3af;
        }

        /* Footer */
        .assignment-footer {
            padding: 1.5rem 2rem;
            background: #f9fafb;
        }

        .footer-actions {
            display: flex;
            gap: 0.875rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border: none;
            border-radius: 7px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn svg {
            width: 16px;
            height: 16px;
        }

        .btn-edit {
            background: #4f46e5;
            color: white;
        }

        .btn-edit:hover {
            background: #4338ca;
        }

        .btn-delete {
            background: white;
            color: #dc2626;
            border: 1px solid #dc2626;
        }

        .btn-delete:hover {
            background: #dc2626;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .assignment-details-page {
                padding: 1rem 0.625rem;
            }

            .assignment-header,
            .description-section,
            .details-section,
            .attachments-section,
            .assignment-footer {
                padding: 1.25rem 1.125rem;
            }

            .assignment-title {
                font-size: 1.375rem;
                line-height: 1.4;
                margin-bottom: 1rem;
            }

            .header-badges {
                gap: 0.5rem;
            }

            .status-change {
                margin-left: 0;
                width: 100%;
                justify-content: space-between;
                margin-top: 0.5rem;
                padding-top: 0.75rem;
                border-top: 1px dashed #e5e7eb;
            }

            .quick-info {
                font-size: 0.8125rem;
                line-height: 1.8;
                gap: 0.5rem 0.625rem;
            }

            .description-content {
                font-size: 0.9375rem;
                line-height: 1.7;
            }

            .details-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .detail-box {
                padding: 0.875rem;
            }

            .footer-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
                padding: 0.75rem 1.25rem;
            }

            .attachment-item {
                flex-direction: column;
            }

            .attachment-preview {
                width: 100%;
                height: 140px;
            }

            .attachment-name {
                font-size: 0.9375rem;
                margin-bottom: 0.5rem;
            }

            .attachment-meta {
                margin-bottom: 0.75rem;
            }
        }

        @media (max-width: 420px) {
            .assignment-title {
                font-size: 1.25rem;
            }

            .btn,
            .back-link {
                font-size: 0.875rem;
            }
        }
    </style>

    <script>
        // ---- Status change from view page ----
        const statusLabels = {
            'pending': 'Pending',
            'processing': 'Processing',
            'completed': 'Completed',
            'on-hold': 'On Hold'
        };

        function showStatusAlert(message, isError = false) {
            const alertBox = document.getElementById('statusAlert');
            alertBox.textContent = message;
            alertBox.classList.toggle('error', isError);
            alertBox.style.display = 'flex';
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 4000);
        }

        document.getElementById('statusSelect')?.addEventListener('change', function(e) {
            const select = e.target;
            const itemId = select.dataset.itemId;
            const newStatus = select.value;
            const previousStatus = select.dataset.previousValue || select.value;

            select.disabled = true;

            fetch(`/projectmng/${itemId}/status`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    select.disabled = false;
                    if (data.success) {
                        const badge = document.getElementById('statusBadge');
                        badge.className = 'status-badge status-' + newStatus;
                        badge.textContent = statusLabels[newStatus] || newStatus;
                        select.dataset.previousValue = newStatus;
                        showStatusAlert(data.message || 'Status updated successfully');
                    } else {
                        select.value = previousStatus;
                        showStatusAlert(data.message || 'Failed to update status', true);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    select.disabled = false;
                    select.value = previousStatus;
                    showStatusAlert('Failed to update status. Please try again.', true);
                });
        });

        // File upload handler
        document.getElementById('detailsFileInput')?.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files.length === 0) return;

            const formData = new FormData();

            // Add all selected files
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            // Show uploading indicator
            const uploadArea = document.querySelector('.upload-area');
            const originalHTML = uploadArea.innerHTML;
            uploadArea.innerHTML = `
                <div style="color: #4f46e5; font-weight: 600;">
                    <svg style="width: 40px; height: 40px; margin: 0 auto 0.5rem; animation: spin 1s linear infinite;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Uploading ${files.length} file(s)...
                </div>
            `;

            // Upload files
            fetch('{{ route('projectmng.uploadAttachments', $item->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to show new attachments
                        location.reload();
                    } else {
                        alert(data.message || 'Upload failed');
                        uploadArea.innerHTML = originalHTML;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Upload failed. Please try again.');
                    uploadArea.innerHTML = originalHTML;
                });

            // Reset input
            e.target.value = '';
        });

        // Delete attachment function
        function deleteAttachment(attachmentId) {
            if (!confirm('Are you sure you want to delete this attachment?')) {
                return;
            }

            fetch(`/projectmng/attachments/${attachmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Delete failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Delete failed. Please try again.');
                });
        }

        // Delete item function
        function deleteItem(itemId) {
            if (!confirm('Are you sure you want to delete this assignment? This action cannot be undone.')) {
                return;
            }

            fetch(`/projectmng/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route('projectmng.list') }}';
                    } else {
                        alert(data.message || 'Delete failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Delete failed. Please try again.');
                });
        }

        // Add spinning animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
