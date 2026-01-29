@extends('layouts')

@section('content')
    <div id="note-details" class="page">
        <div class="page-header">
            <h1>Note Details</h1>
            <div class="header-actions">
                <a href="{{ route('notes.index') }}" class="btn btn-secondary">← Back to Notes</a>
            </div>
        </div>

        @include('design.includes.alert')

        <div class="detail-card">
            <div class="detail-header">
                <div class="detail-title">
                    {{ $note->title }}
                    @if ($note->is_private)
                        <span style="color: #f56565; font-size: 14px; margin-left: 10px;">🔒 Private</span>
                    @endif
                </div>
                <div class="detail-actions">
                    @if ($note->created_by == auth()->id())
                        <button class="action-btn edit"
                            onclick="window.location.href='{{ route('notes.edit', $note->id) }}'">
                            ✏️ Edit
                        </button>
                        <button class="action-btn delete" onclick="deleteNote({{ $note->id }})">
                            🗑️ Delete
                        </button>
                    @endif
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Note ID</div>
                    <div class="detail-value">#{{ str_pad($note->id, 3, '0', STR_PAD_LEFT) }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Project</div>
                    <div class="detail-value">{{ $note->project->name }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Created By</div>
                    <div class="detail-value">{{ $note->creator->name }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Created Date</div>
                    <div class="detail-value">{{ $note->created_at->format('F d, Y h:i A') }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Last Updated</div>
                    <div class="detail-value">{{ $note->updated_at->format('F d, Y h:i A') }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Pinned</div>
                    <div class="detail-value">{{ $note->is_pinned ? '✅ Yes' : '❌ No' }}</div>
                </div>

                <div class="detail-item full-width">
                    <div class="detail-label">Content</div>
                    <div class="detail-value" style="white-space: pre-wrap;">
                        {{ $note->content ?: 'No content' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-secondary {
            padding: 10px 20px;
            background: #e2e8f0;
            color: #2d3748;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }
    </style>
@endsection
