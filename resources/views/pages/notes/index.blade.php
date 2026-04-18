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
                        </h3>

                        {{-- Badges below title --}}
                        @if ($note->is_private || ($note->attachments && $note->attachments->count() > 0))
                            <div class="note-badges">
                                @if ($note->is_private)
                                    <span class="badge badge-private">🔒 Private</span>
                                @endif
                                @if ($note->attachments && $note->attachments->count() > 0)
                                    <span class="badge badge-attach">📎 {{ $note->attachments->count() }}</span>
                                @endif
                            </div>
                        @endif

                        {{-- Excerpt --}}
                        <p class="note-excerpt">
                            {{ Str::limit(strip_tags($note->content ?? 'No content'), 150) }}
                        </p>

                        {{-- Footer: author LEFT, date RIGHT --}}
                        <div class="note-footer-row">
                            <div class="note-author-wrap">
                                <span
                                    class="note-author-avatar">{{ strtoupper(substr($note->creator->name, 0, 1)) }}</span>
                                <span class="note-author">{{ $note->creator->name }}</span>
                            </div>
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
        /* ── Font stack ── */
        :root {
            --font-serif: source-serif-pro, Georgia, Cambria, "Times New Roman", Times, serif;
            --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --text-primary: #1a1a1a;
            --text-secondary: #292929;
            --text-muted: #6b7280;
            --text-faint: #a0aec0;
            --border: #e8eaf0;
            --surface: #faf9f7;
        }

        /* ══ DESKTOP: grid of cards ══ */
        .notes-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 16px;
        }

        /* ── Card ── */
        .note-card {
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
            cursor: pointer;
            transition: border-color 0.15s, box-shadow 0.15s, transform 0.15s;
        }

        .note-card:hover {
            border-color: #c9d0e8;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .note-card:active {
            transform: translateY(0);
        }

        /* ── Thumbnail ── */
        .note-thumb {
            width: 100%;
            height: 168px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .note-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.35s ease;
        }

        .note-card:hover .note-thumb img {
            transform: scale(1.04);
        }

        .note-thumb-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f5f9;
        }

        .note-thumb-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── Card body ── */
        .note-body {
            flex: 1;
            min-width: 0;
            padding: 18px 20px 20px;
            display: flex;
            flex-direction: column;
        }

        /* Row 1: project label + action buttons */
        .note-row-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 10px;
        }

        /* Project label — Medium uses a subtle category tag above titles */
        .note-project {
            font-family: var(--font-sans);
            font-size: 11px;
            font-weight: 700;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .note-actions {
            display: flex;
            align-items: center;
            gap: 2px;
            flex-shrink: 0;
        }

        /* ── Title — serif, like a Medium headline ── */
        .note-title {
            font-family: var(--font-serif);
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.35;
            letter-spacing: -0.01em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin: 0 0 8px 0;
        }

        /* ── Badges — below title, separate row ── */
        .note-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 10px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            font-family: var(--font-sans);
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500;
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

        /* ── Excerpt — slightly warmer, more readable ── */
        .note-excerpt {
            font-family: var(--font-serif);
            font-size: 14.5px;
            color: #4a4a4a;
            line-height: 1.7;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
            margin: 0 0 16px 0;
            letter-spacing: 0.001em;
        }

        /* ── Footer row ── */
        .note-footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid #f0f0f0;
            margin-top: auto;
            font-family: var(--font-sans);
        }

        .note-author-wrap {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        /* Small avatar circle — Medium-style author treatment */
        .note-author-avatar {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #e8eaf6;
            color: #4f46e5;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-family: var(--font-sans);
        }

        .note-author {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .note-date {
            font-size: 12px;
            color: var(--text-faint);
            white-space: nowrap;
        }

        /* ── Filter bar ── */
        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
            margin-bottom: 4px;
            gap: 15px;
            font-family: var(--font-sans);
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
            font-family: var(--font-sans);
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
            opacity: 0.35;
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
            font-family: var(--font-sans);
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

            .note-card {
                flex-direction: row;
                align-items: stretch;
            }

            .note-thumb {
                width: 80px;
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

            .note-title {
                font-size: 16px;
                -webkit-line-clamp: 2;
                margin-bottom: 6px;
            }

            .note-badges {
                margin-bottom: 6px;
            }

            .note-excerpt {
                font-size: 13px;
                -webkit-line-clamp: 2;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                overflow: hidden;
                margin-bottom: 10px;
            }

            .note-footer-row {
                padding-top: 8px;
            }

            .note-date {
                display: none;
            }

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
