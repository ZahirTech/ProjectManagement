@extends('layouts')

@section('content')
    <article class="note-article">
        <div class="note-container">
            <!-- Back button - subtle, top left -->
            <a href="{{ route('notes.index') }}" class="back-link">
                ← Back to Notes
            </a>

            <!-- Note Header -->
            <header class="note-header">
                <h1 class="note-title">{{ $note->title }}</h1>

                <div class="note-meta">
                    <span class="meta-item">
                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ $note->creator->name }}
                    </span>
                    <span class="meta-divider">·</span>
                    <span class="meta-item">
                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $note->created_at->format('F d, Y') }}
                    </span>
                    <span class="meta-divider">·</span>
                    <span class="meta-item">
                        <svg class="meta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        {{ $note->project->name }}
                    </span>
                    @if ($note->is_private)
                        <span class="meta-divider">·</span>
                        <span class="meta-item private-badge">
                            🔒 Private
                        </span>
                    @endif
                    @if ($note->is_pinned)
                        <span class="meta-divider">·</span>
                        <span class="meta-item pinned-badge">
                            📌 Pinned
                        </span>
                    @endif
                </div>
            </header>

            @include('design.includes.alert')

            <!-- Main Content - The Star of the Show -->
            <div class="note-content">
                {{ $note->content ?: 'No content available.' }}
            </div>

            <!-- Attachments Section -->
            @if ($note->attachments->count() > 0)
                <div class="attachments-section">
                    <h3 class="attachments-title">
                        <svg class="attachments-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        Attachments ({{ $note->attachments->count() }})
                    </h3>
                    <div class="attachments-grid">
                        @foreach ($note->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                class="attachment-card">
                                <div class="attachment-icon-large">
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
                                </div>
                                <div class="attachment-card-info">
                                    <div class="attachment-card-name">{{ $attachment->original_filename }}</div>
                                    <div class="attachment-card-meta">
                                        {{ $attachment->formatted_size }} · {{ $attachment->uploader->name }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Footer Actions -->
            @if ($note->created_by == auth()->id())
                <footer class="note-footer">
                    <div class="note-actions">
                        <button class="action-btn action-btn-edit"
                            onclick="window.location.href='{{ route('notes.edit', $note->id) }}'">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Note
                        </button>
                        <button class="action-btn action-btn-delete" onclick="deleteNote({{ $note->id }})">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Note
                        </button>
                    </div>
                    <div class="note-timestamp">
                        Last updated {{ $note->updated_at->diffForHumans() }}
                    </div>
                </footer>
            @endif
        </div>
    </article>

    <script>
        function deleteNote(noteId) {
            if (!confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
                return;
            }

            fetch(`/notes/${noteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route('notes.index') }}';
                    } else {
                        alert(data.message || 'Delete failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Delete failed. Please try again.');
                });
        }
    </script>

    <style>
        .note-article {
            min-height: 100vh;
            background: #fafafa;
            padding: 1.5rem 1rem;
        }

        .note-container {
            max-width: 720px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #111827;
        }

        .note-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .note-title {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.25;
            color: #111827;
            margin: 0 0 1rem 0;
            letter-spacing: -0.02em;
        }

        .note-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .meta-icon {
            width: 15px;
            height: 15px;
            opacity: 0.7;
        }

        .meta-divider {
            color: #d1d5db;
        }

        .private-badge {
            color: #dc2626;
        }

        .pinned-badge {
            color: #ea580c;
        }

        /* Main Content - Beautiful Typography */
        .note-content {
            font-size: 1.0625rem;
            line-height: 1.75;
            color: #374151;
            white-space: pre-line;
            word-wrap: break-word;
            margin-bottom: 2.5rem;
        }

        /* Attachments Section */
        .attachments-section {
            margin-bottom: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .attachments-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1rem;
        }

        .attachments-icon {
            width: 20px;
            height: 20px;
        }

        .attachments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .attachment-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.25rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }

        .attachment-card:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .attachment-icon-large {
            font-size: 3rem;
            margin-bottom: 0.75rem;
        }

        .attachment-card-info {
            text-align: center;
            width: 100%;
        }

        .attachment-card-name {
            font-weight: 500;
            color: #111827;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            word-break: break-word;
        }

        .attachment-card-meta {
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* Footer */
        .note-footer {
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .note-actions {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-btn svg {
            width: 16px;
            height: 16px;
        }

        .action-btn-edit {
            color: #3b82f6;
            border-color: #3b82f6;
        }

        .action-btn-edit:hover {
            background: #3b82f6;
            color: white;
        }

        .action-btn-delete {
            color: #ef4444;
            border-color: #ef4444;
        }

        .action-btn-delete:hover {
            background: #ef4444;
            color: white;
        }

        .note-timestamp {
            font-size: 0.8125rem;
            color: #9ca3af;
            font-style: italic;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .note-article {
                padding: 1rem 0.5rem;
            }

            .note-container {
                padding: 1.5rem 1.25rem;
            }

            .note-title {
                font-size: 1.75rem;
            }

            .note-content {
                font-size: 1rem;
            }

            .note-actions {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
            }

            .attachments-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
