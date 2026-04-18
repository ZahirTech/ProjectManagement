@extends('layouts')

@section('content')
    <div id="notes" class="page">
        <div class="page-header">
            <h1>Notes</h1>
            <div class="header-actions">
                <a href="{{ route('notes.create') }}" class="btn btn-primary">
                    ➕ Create New Note
                </a>
            </div>
        </div>

        @include('design.includes.alert')

        <!-- Compact Filter Bar -->
        <div class="filter-bar">
            <div class="filter-left">
                <select id="notesProjectSelect" class="compact-select">
                    <option value="all">All Projects</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-right">
                <label class="toggle-switch">
                    <input type="checkbox" id="myNotesToggle" {{ request('my_notes') ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                    <span class="toggle-label">My Notes Only</span>
                </label>
            </div>
        </div>

        <!-- Notes List -->
        <div class="notes-list">
            @forelse($notes as $note)
                {{--
                    Resolve a cover image: first attached image, or null.
                    You can also store a dedicated cover_image field on the note.
                --}}
                @php
                    $coverImage = $note->attachments->firstWhere(fn($a) => str_starts_with($a->mime_type, 'image/'));
                @endphp

                <div class="note-card" onclick="window.location.href='{{ route('notes.show', $note->id) }}'">

                    {{-- ── Thumbnail strip ── --}}
                    <div class="note-thumb">
                        @if ($coverImage)
                            <img src="{{ asset('storage/' . $coverImage->file_path) }}" alt="{{ $note->title }}"
                                loading="lazy">
                        @else
                            <div class="note-thumb-placeholder">
                                <img src="https://miro.medium.com/v2/resize:fit:499/format:webp/1*nbyKjUXHHvJesxAT0aCenQ.jpeg"
                                    alt="{{ $note->title }}" loading="lazy">

                                {{-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <line x1="16" y1="13" x2="8" y2="13" />
                                    <line x1="16" y1="17" x2="8" y2="17" />
                                    <polyline points="10 9 9 9 8 9" />
                                </svg> --}}
                            </div>
                        @endif
                    </div>

                    {{-- ── Card body ── --}}
                    <div class="note-body">
                        <div class="note-row-top">
                            <span class="note-project">{{ $note->project->name }}</span>
                            <div class="note-badges">
                                @if ($note->is_private)
                                    <span class="badge badge-private">🔒 Private</span>
                                @endif
                                @if ($note->attachments && $note->attachments->count() > 0)
                                    <span class="badge badge-attach">📎 {{ $note->attachments->count() }}</span>
                                @endif
                            </div>
                        </div>

                        <h3 class="note-title">{{ $note->title }}</h3>

                        <p class="note-excerpt">
                            {{ Str::limit(strip_tags($note->content ?? 'No content'), 150) }}
                        </p>

                        <div class="note-footer-row">
                            <span class="note-author">{{ $note->creator->name }}</span>
                            <div class="note-right">
                                <span class="note-date">{{ $note->updated_at->diffForHumans() }}</span>
                                <div class="note-actions" onclick="event.stopPropagation()">
                                    <button class="icon-btn pin {{ $note->is_pinned ? 'pinned' : '' }}"
                                        onclick="toggleNotePin({{ $note->id }})"
                                        title="{{ $note->is_pinned ? 'Unpin' : 'Pin to Dashboard' }}">📌</button>
                                    @if ($note->created_by == auth()->id())
                                        <button class="icon-btn edit"
                                            onclick="window.location.href='{{ route('notes.edit', $note->id) }}'"
                                            title="Edit">✏️</button>
                                        <button class="icon-btn delete" onclick="deleteNote({{ $note->id }})"
                                            title="Delete">🗑️</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">📝</div>
                    <h3>No Notes Found</h3>
                    <p>Create your first note to get started</p>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        /* ══ DESKTOP: grid of cards ══ */
        .notes-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 16px;
        }

        /* ── Card: vertical (image on top) on desktop ── */
        .note-card {
            display: flex;
            flex-direction: column;
            /* stack thumb → body vertically */
            background: white;
            border-radius: 12px;
            border: 1px solid #e8eaf0;
            overflow: hidden;
            cursor: pointer;
            transition: border-color 0.15s, box-shadow 0.15s, transform 0.15s;
            text-decoration: none;
            color: inherit;
        }

        .note-card:hover {
            border-color: #c9d0e8;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.09);
            transform: translateY(-2px);
        }

        .note-card:active {
            transform: translateY(0);
        }

        /* ── Thumbnail — full-width banner on desktop ── */
        .note-thumb {
            width: 100%;
            height: 160px;
            /* fixed banner height */
            flex-shrink: 0;
            overflow: hidden;
        }

        .note-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .note-card:hover .note-thumb img {
            transform: scale(1.03);
        }

        /* Placeholder when no cover image */
        .note-thumb-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f5f9;
        }

        .note-thumb-placeholder svg {
            width: 32px;
            height: 32px;
            color: #c0c6d8;
            stroke: currentColor;
        }

        /* ── Card body ── */
        .note-body {
            flex: 1;
            min-width: 0;
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .note-row-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .note-project {
            font-size: 11px;
            font-weight: 600;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .note-badges {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-shrink: 0;
        }

        .badge {
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 500;
        }

        .badge-private {
            background: #fff0f0;
            color: #c53030;
        }

        .badge-attach {
            background: #f0f4ff;
            color: #4361c2;
        }

        .note-title {
            font-size: 15px;
            font-weight: 600;
            color: #1a202c;
            line-height: 1.35;
            /* allow 2 lines on grid cards */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .note-excerpt {
            font-size: 13px;
            color: #718096;
            line-height: 1.55;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
            /* push footer to bottom */
        }

        /* ── Footer row ── */
        .note-footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            margin-top: auto;
        }

        .note-author {
            font-size: 12px;
            color: #a0aec0;
            font-weight: 500;
        }

        .note-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .note-date {
            font-size: 12px;
            color: #a0aec0;
            white-space: nowrap;
        }

        .note-actions {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        /* ── Filter bar (unchanged) ── */
        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            margin-bottom: 4px;
            gap: 15px;
        }

        .filter-left {
            flex: 0 0 auto;
        }

        .compact-select {
            padding: 8px 32px 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: white;
            font-size: 14px;
            cursor: pointer;
            min-width: 200px;
        }

        .filter-right {
            flex: 0 0 auto;
        }

        /* Toggle switch */
        .toggle-switch {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }

        .toggle-switch input[type="checkbox"] {
            display: none;
        }

        .toggle-slider {
            position: relative;
            width: 44px;
            height: 24px;
            background: #cbd5e0;
            border-radius: 24px;
            transition: background 0.3s;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: white;
            top: 3px;
            left: 3px;
            transition: transform 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .toggle-switch input:checked+.toggle-slider {
            background: #4299e1;
        }

        .toggle-switch input:checked+.toggle-slider::before {
            transform: translateX(20px);
        }

        .toggle-label {
            font-size: 14px;
            font-weight: 500;
            color: #2d3748;
        }

        /* Pin / action buttons */
        .icon-btn {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            opacity: 0.4;
            transition: opacity 0.15s, background 0.15s;
        }

        .icon-btn:hover {
            opacity: 1;
            background: #f0f2f8;
        }

        .icon-btn.pin.pinned {
            opacity: 1;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            border: 1px dashed #e2e8f0;
            color: #a0aec0;
        }

        .empty-state-icon {
            font-size: 2.5rem;
            margin-bottom: 12px;
        }

        .empty-state h3 {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 6px;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* ══ MOBILE: switch to horizontal list ══ */
        @media (max-width: 640px) {

            /* Single column list */
            .notes-list {
                grid-template-columns: 1fr;
                gap: 6px;
            }

            /* Card becomes horizontal again */
            .note-card {
                flex-direction: row;
                align-items: stretch;
            }

            /* Thumb becomes left strip */
            .note-thumb {
                width: 64px;
                height: auto;
                flex-shrink: 0;
            }

            .note-card:hover .note-thumb img {
                transform: none;
            }

            .note-thumb-placeholder svg {
                width: 22px;
                height: 22px;
            }

            .note-body {
                padding: 11px 13px;
                gap: 4px;
            }

            /* Show full title on mobile — no truncation */
            .note-title {
                font-size: 14px;
                -webkit-line-clamp: unset;
                white-space: normal;
                text-overflow: unset;
                display: block;
                overflow: visible;
            }

            /* Excerpt stays truncated (1 line) */
            .note-excerpt {
                -webkit-line-clamp: 1;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            /* Keep the border line, just tighten spacing */
            .note-footer-row {
                padding-top: 8px;
                border-top: 1px solid #e2e8f0;
                margin-top: 4px;
            }

            .note-date {
                display: none;
            }

            /* Filter bar stacks vertically */
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .compact-select {
                width: 100%;
                min-width: auto;
            }

            .filter-right {
                width: 100%;
            }

            .toggle-switch {
                justify-content: space-between;
            }
        }
    </style>

    <script>
        document.getElementById('notesProjectSelect')?.addEventListener('change', function() {
            const url = new URL(window.location);
            url.searchParams.set('project_id', this.value);
            window.location.href = url.toString();
        });

        document.getElementById('myNotesToggle')?.addEventListener('change', function() {
            const url = new URL(window.location);
            if (this.checked) url.searchParams.set('my_notes', '1');
            else url.searchParams.delete('my_notes');
            window.location.href = url.toString();
        });
    </script>
@endsection
