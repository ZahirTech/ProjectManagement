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

                    {{-- ── Thumbnail banner ── --}}
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

                        {{-- Row 1: project label LEFT, action buttons RIGHT --}}
                        <div class="note-row-top">
                            <span class="note-project">{{ $note->project->name }}</span>
                            <div class="note-actions" onclick="event.stopPropagation()">
                                <button class="icon-btn pin {{ $note->is_pinned ? 'pinned' : '' }}"
                                    onclick="toggleNotePin({{ $note->id }})"
                                    title="{{ $note->is_pinned ? 'Unpin' : 'Pin to Dashboard' }}">📌</button>
                                @if ($note->created_by == auth()->id())
                                    <button class="icon-btn"
                                        onclick="window.location.href='{{ route('notes.edit', $note->id) }}'"
                                        title="Edit">✏️</button>
                                    <button class="icon-btn" onclick="deleteNote({{ $note->id }})"
                                        title="Delete">🗑️</button>
                                @endif
                            </div>
                        </div>

                        {{-- Title --}}
                        <h3 class="note-title">
                            {{ $note->title }}
                            @if ($note->is_private)
                                <span class="badge badge-private">🔒 Private</span>
                            @endif
                            @if ($note->attachments && $note->attachments->count() > 0)
                                <span class="badge badge-attach">📎 {{ $note->attachments->count() }}</span>
                            @endif
                        </h3>

                        {{-- Excerpt --}}
                        <p class="note-excerpt">
                            {{ Str::limit(strip_tags($note->content ?? 'No content'), 150) }}
                        </p>

                        {{-- Footer: author LEFT, date RIGHT — clean, no buttons --}}
                        <div class="note-footer-row">
                            <span class="note-author">{{ $note->creator->name }}</span>
                            <span class="note-date">{{ $note->updated_at->diffForHumans() }}</span>
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
            padding: 16px 20px 18px;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        /* Row 1: project name + action buttons */
        .note-row-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 10px;
        }

        .note-project {
            font-size: 11px;
            font-weight: 700;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        /* Action buttons sit at top-right — same as original design */
        .note-actions {
            display: flex;
            align-items: center;
            gap: 2px;
            flex-shrink: 0;
        }

        /* Badges inline with title */
        .note-title {
            font-size: 17px;
            font-weight: 700;
            color: #1a202c;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 500;
            vertical-align: middle;
            margin-left: 4px;
            white-space: nowrap;
        }

        .badge-private {
            background: #fff0f0;
            color: #c53030;
        }

        .badge-attach {
            background: #f0f4ff;
            color: #4361c2;
        }

        /* Excerpt — darker and more readable */
        .note-excerpt {
            font-size: 13.5px;
            color: #4a5568;
            line-height: 1.65;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
            margin-bottom: 14px;
        }

        /* ── Footer row: just author + date, clean border on top ── */
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

        .note-date {
            font-size: 12px;
            color: #a0aec0;
            white-space: nowrap;
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
            .notes-list {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            /* Card goes horizontal */
            .note-card {
                flex-direction: row;
                align-items: stretch;
            }

            /* Thumb = left strip */
            .note-thumb {
                width: 72px;
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
                padding: 12px 14px 14px;
            }

            .note-row-top {
                margin-bottom: 6px;
            }

            /* Full title — no truncation on mobile */
            .note-title {
                font-size: 15px;
                font-weight: 700;
                -webkit-line-clamp: unset;
                display: block;
                overflow: visible;
                white-space: normal;
                margin-bottom: 6px;
            }

            /* Excerpt: 2 lines on mobile */
            .note-excerpt {
                font-size: 13px;
                -webkit-line-clamp: 2;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                overflow: hidden;
                margin-bottom: 10px;
            }

            /* Footer border stays visible */
            .note-footer-row {
                padding-top: 8px;
                border-top: 1px solid #e2e8f0;
                margin-top: 0;
            }

            /* Hide date on mobile to save space, keep author */
            .note-date {
                display: none;
            }

            /* Filter bar stacks */
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
