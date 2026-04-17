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

        <!-- Notes Grid -->
        <div class="notes-grid">
            @forelse($notes as $note)
                <div class="note-card">
                    <div class="note-header">
                        <div class="note-project">{{ $note->project->name }}</div>
                        <div class="note-actions">
                            <button class="icon-btn pin {{ $note->is_pinned ? 'pinned' : '' }}"
                                onclick="toggleNotePin({{ $note->id }})"
                                title="{{ $note->is_pinned ? 'Unpin' : 'Pin to Dashboard' }}">
                                📌
                            </button>
                            @if ($note->created_by == auth()->id())
                                <button class="icon-btn edit"
                                    onclick="window.location.href='{{ route('notes.edit', $note->id) }}'"
                                    title="Edit">✏️</button>
                                <button class="icon-btn delete" onclick="deleteNote({{ $note->id }})"
                                    title="Delete">🗑️</button>
                            @endif
                        </div>
                    </div>

                    <div class="note-content" onclick="window.location.href='{{ route('notes.show', $note->id) }}'">
                        <h3 class="note-title">
                            {{ $note->title }}
                            @if ($note->is_private)
                                <span style="color: #f56565; font-size: 14px;">🔒</span>
                            @endif
                            @if ($note->attachments && $note->attachments->count() > 0)
                                <span class="attachment-badge" title="{{ $note->attachments->count() }} attachment(s)">
                                    📎 {{ $note->attachments->count() }}
                                </span>
                            @endif
                        </h3>
                        <p class="note-excerpt">
                            {{ Str::limit(strip_tags($note->content ?? 'No content'), 150) }}
                        </p>
                    </div>

                    <div class="note-footer">
                        <span class="note-author">{{ $note->creator->name }}</span>
                        <span class="note-date">{{ $note->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1/-1;">
                    <div class="empty-state-icon">📝</div>
                    <h3>No Notes Found</h3>
                    <p>Create your first note to get started</p>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .notes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .note-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            gap: 15px;
            overflow: hidden;
        }

        .note-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .note-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .note-project {
            font-size: 12px;
            color: #667eea;
            font-weight: 600;
            text-transform: uppercase;
        }

        .note-actions {
            display: flex;
            gap: 5px;
        }

        .note-content {
            flex: 1;
            cursor: pointer;
        }

        .note-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .attachment-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 12px;
            background: #ebf8ff;
            color: #2b6cb0;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 500;
        }

        .note-excerpt {
            color: #718096;
            font-size: 14px;
            line-height: 1.5;
        }

        .note-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #a0aec0;
        }

        .note-author {
            font-weight: 500;
        }

        /* Compact Filter Bar */
        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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

        /* Toggle Switch */
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

        /* Pin Button */
        .icon-btn.pin {
            opacity: 0.4;
            transition: all 0.2s;
        }

        .icon-btn.pin:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .icon-btn.pin.pinned {
            opacity: 1;
            color: #f56565;
            animation: pinPulse 0.6s ease-in-out;
        }

        @keyframes pinPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }
        }

        /* Responsive */
        @media (max-width: 640px) {
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
        // Notes project filter
        document.getElementById('notesProjectSelect')?.addEventListener('change', function() {
            const url = new URL(window.location);
            url.searchParams.set('project_id', this.value);
            window.location.href = url.toString();
        });

        // My notes toggle
        document.getElementById('myNotesToggle')?.addEventListener('change', function() {
            const url = new URL(window.location);
            if (this.checked) {
                url.searchParams.set('my_notes', '1');
            } else {
                url.searchParams.delete('my_notes');
            }
            window.location.href = url.toString();
        });
    </script>
@endsection
