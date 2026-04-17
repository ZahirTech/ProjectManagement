@extends('layouts')

@section('content')
    {{-- Syntax highlighting CSS (GitHub Dark theme) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">

    <article class="note-article">
        <div class="note-container">
            <!-- Back button -->
            <a href="{{ route('notes.index') }}" class="back-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
                Back to Notes
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
                        <span class="meta-item private-badge">🔒 Private</span>
                    @endif
                    @if ($note->is_pinned)
                        <span class="meta-divider">·</span>
                        <span class="meta-item pinned-badge">📌 Pinned</span>
                    @endif
                </div>
            </header>

            @include('design.includes.alert')

            <!-- Main Content - Rich Text Rendered -->
            <div class="note-content rich-content">
                {!! $note->content ?: '<p class="empty-note">No content available.</p>' !!}
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
                    <div class="attachments-list">
                        @foreach ($note->attachments as $attachment)
                            <div class="attachment-item" id="attachment-{{ $attachment->id }}">
                                <div class="attachment-preview">
                                    @if (str_starts_with($attachment->mime_type, 'image/'))
                                        <img src="{{ asset('storage/' . $attachment->file_path) }}"
                                            alt="{{ $attachment->original_filename }}" class="attachment-image">
                                    @elseif(str_contains($attachment->mime_type, 'pdf'))
                                        <div class="file-icon-large">📄</div>
                                    @elseif(str_contains($attachment->mime_type, 'word'))
                                        <div class="file-icon-large">📝</div>
                                    @elseif(str_contains($attachment->mime_type, 'sheet'))
                                        <div class="file-icon-large">📊</div>
                                    @else
                                        <div class="file-icon-large">📎</div>
                                    @endif
                                </div>
                                <div class="attachment-details">
                                    <div class="attachment-name">{{ $attachment->original_filename }}</div>
                                    <div class="attachment-meta">{{ $attachment->formatted_size }} ·
                                        {{ $attachment->uploader->name }}</div>
                                    <div class="attachment-actions">
                                        <a href="{{ asset('storage/' . $attachment->file_path) }}"
                                            download="{{ $attachment->original_filename }}"
                                            class="att-action-btn download-btn">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download
                                        </a>
                                        @if ($note->created_by == auth()->id())
                                            <button class="att-action-btn delete-btn"
                                                onclick="deleteNoteAttachment({{ $attachment->id }})">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
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
                    <div class="note-timestamp">Last updated {{ $note->updated_at->diffForHumans() }}</div>
                </footer>
            @endif
        </div>
    </article>

    {{-- highlight.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <script>
        // ─── Syntax highlighting ───────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {

            // Apply highlight.js to all code blocks
            document.querySelectorAll('.rich-content pre code').forEach(function(block) {
                hljs.highlightElement(block)
            })

            // Add copy buttons + language label to each code block
            document.querySelectorAll('.rich-content pre').forEach(function(pre) {
                // Determine language
                const code = pre.querySelector('code')
                let lang = ''
                if (code) {
                    const classes = Array.from(code.classList)
                    const langClass = classes.find(c => c.startsWith('language-'))
                    if (langClass) lang = langClass.replace('language-', '').toUpperCase()
                    if (!lang && code.result && code.result.language) lang = code.result.language
                        .toUpperCase()
                }

                // Build header bar
                const header = document.createElement('div')
                header.className = 'code-block-header'
                header.innerHTML = `
                    <span class="code-block-lang">${lang || 'CODE'}</span>
                    <button class="copy-code-btn" title="Copy code">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                        Copy
                    </button>
                `
                pre.insertBefore(header, pre.firstChild)

                // Copy button behaviour
                header.querySelector('.copy-code-btn').addEventListener('click', function() {
                    const text = code ? code.innerText : pre.innerText
                    navigator.clipboard.writeText(text).then(() => {
                        this.innerHTML =
                            `<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Copied!`
                        this.classList.add('copied')
                        setTimeout(() => {
                            this.innerHTML =
                                `<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg> Copy`
                            this.classList.remove('copied')
                        }, 2000)
                    })
                })
            })

            // Make external links open in new tab
            document.querySelectorAll('.rich-content a').forEach(function(link) {
                if (link.hostname !== window.location.hostname) {
                    link.setAttribute('target', '_blank')
                    link.setAttribute('rel', 'noopener noreferrer')
                }
            })

            // Add line numbers toggle (optional, on demand)
            document.querySelectorAll('.rich-content pre').forEach(function(pre) {
                pre.addEventListener('dblclick', function() {
                    this.classList.toggle('show-line-numbers')
                })
            })
        })

        // ─── Delete note ──────────────────────────────────────────────────
        function deleteNote(noteId) {
            if (!confirm('Are you sure you want to delete this note? This action cannot be undone.')) return
            fetch(`/notes/${noteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(r => r.json()).then(data => {
                if (data.success) window.location.href = '{{ route('notes.index') }}'
                else alert(data.message || 'Delete failed')
            }).catch(() => alert('Delete failed. Please try again.'))
        }

        // ─── Delete attachment ─────────────────────────────────────────────
        function deleteNoteAttachment(attachmentId) {
            if (!confirm('Are you sure you want to delete this attachment?')) return
            fetch(`/notes/attachments/${attachmentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(r => r.json()).then(data => {
                if (data.success) document.getElementById(`attachment-${attachmentId}`).remove()
                else alert(data.message || 'Delete failed')
            }).catch(() => alert('Delete failed. Please try again.'))
        }
    </script>

    <style>
        /* ─── Page Layout ─── */
        .note-article {
            min-height: 100vh;
            background: #f7f8fc;
            padding: 1rem 0.5rem;
            /* reduced from 2rem 1rem */
        }

        .note-container {
            max-width: 780px;
            margin: 0 auto;
            background: white;
            padding: 2rem 2rem;
            /* reduced from 2.5rem 3rem */
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.875rem;
            margin-bottom: 2rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #111827;
        }

        /* ─── Header ─── */
        .note-header {
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f1f3f9;
        }

        .note-title {
            font-size: 2.125rem;
            font-weight: 800;
            line-height: 1.2;
            color: #111827;
            margin: 0 0 1.25rem 0;
            letter-spacing: -0.03em;
            font-family: 'Georgia', serif;
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
            width: 14px;
            height: 14px;
            opacity: 0.7;
        }

        .meta-divider {
            color: #d1d5db;
        }

        .private-badge {
            color: #dc2626;
            font-weight: 500;
        }

        .pinned-badge {
            color: #ea580c;
            font-weight: 500;
        }

        /* ─── Rich Text Content ─── */
        .rich-content {
            font-size: 1.0625rem;
            line-height: 1.8;
            color: #374151;
            font-family: 'Georgia', serif;
            margin-bottom: 2.5rem;
            word-wrap: break-word;
        }

        .rich-content h1 {
            font-size: 2em;
            font-weight: 800;
            margin: 1.5em 0 0.5em;
            color: #111827;
            letter-spacing: -0.02em;
            border-bottom: 2px solid #f1f3f9;
            padding-bottom: 0.3em;
        }

        .rich-content h2 {
            font-size: 1.5em;
            font-weight: 700;
            margin: 1.4em 0 0.4em;
            color: #1f2937;
        }

        .rich-content h3 {
            font-size: 1.25em;
            font-weight: 700;
            margin: 1.2em 0 0.4em;
            color: #1f2937;
        }

        .rich-content h4 {
            font-size: 1.1em;
            font-weight: 600;
            margin: 1em 0 0.3em;
            color: #374151;
        }

        .rich-content p {
            margin: 0 0 1em;
        }

        .rich-content p:last-child {
            margin-bottom: 0;
        }

        /* Links */
        .rich-content a {
            color: #4f46e5;
            text-decoration: underline;
            text-underline-offset: 3px;
            transition: color 0.15s;
        }

        .rich-content a:hover {
            color: #3730a3;
        }

        /* Strong / em */
        .rich-content strong {
            font-weight: 700;
            color: #111827;
        }

        .rich-content em {
            font-style: italic;
        }

        .rich-content s {
            text-decoration: line-through;
            color: #9ca3af;
        }

        .rich-content u {
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        /* Highlight */
        .rich-content mark {
            background: #fef08a;
            padding: 1px 3px;
            border-radius: 3px;
            color: #713f12;
        }

        /* Inline code */
        .rich-content code {
            background: #f1f5f9;
            color: #c0392b;
            padding: 2px 6px;
            border-radius: 5px;
            font-family: 'Fira Code', 'Cascadia Code', 'Consolas', monospace;
            font-size: 0.875em;
            border: 1px solid #e2e8f0;
        }

        /* ─── Code Block (the star) ─── */
        .rich-content pre {
            background: #0d1117;
            border-radius: 10px;
            margin: 1.5em 0;
            overflow: hidden;
            font-family: 'Fira Code', 'Cascadia Code', 'Consolas', monospace;
            font-size: 0.875em;
            border: 1px solid #21262d;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        /* Code block header bar (injected by JS) */
        .code-block-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #161b22;
            padding: 8px 16px;
            border-bottom: 1px solid #21262d;
        }

        .code-block-lang {
            font-size: 11px;
            font-family: monospace;
            color: #58a6ff;
            letter-spacing: 0.08em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .copy-code-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #21262d;
            color: #8b949e;
            border: 1px solid #30363d;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 12px;
            cursor: pointer;
            font-family: monospace;
            transition: all 0.15s;
            white-space: nowrap;
            /* prevent wrapping */
            flex-shrink: 0;
            /* don't let it grow/shrink weirdly */
            line-height: 1;
        }

        .copy-code-btn svg {
            width: 14px !important;
            height: 14px !important;
            flex-shrink: 0;
            display: block;
        }

        .copy-code-btn:hover {
            background: #30363d;
            color: #c9d1d9;
            border-color: #8b949e;
        }

        .copy-code-btn.copied {
            background: #1a7f37;
            color: #fff;
            border-color: #2ea043;
        }

        .rich-content pre code {
            display: block;
            background: none !important;
            color: #e6edf3;
            padding: 16px 20px;
            border-radius: 0;
            border: none;
            overflow-x: auto;
            white-space: pre;
            line-height: 1.6;
            font-size: inherit;
            tab-size: 4;
        }

        /* Line numbers on double-click */
        .rich-content pre.show-line-numbers code {
            counter-reset: line;
        }

        .rich-content pre.show-line-numbers code .hljs-ln-n::before {
            counter-increment: line;
            content: counter(line);
            display: inline-block;
            width: 2em;
            margin-right: 1.5em;
            text-align: right;
            color: #4d5566;
            border-right: 1px solid #2d333b;
            padding-right: 1em;
            user-select: none;
        }

        /* Scrollbar styling for code blocks */
        .rich-content pre code::-webkit-scrollbar {
            height: 6px;
        }

        .rich-content pre code::-webkit-scrollbar-track {
            background: #161b22;
        }

        .rich-content pre code::-webkit-scrollbar-thumb {
            background: #30363d;
            border-radius: 3px;
        }

        .rich-content pre code::-webkit-scrollbar-thumb:hover {
            background: #484f58;
        }

        /* ─── Blockquote ─── */
        .rich-content blockquote {
            border-left: 4px solid #667eea;
            padding: 10px 20px;
            margin: 1.2em 0;
            background: linear-gradient(to right, #f8f7ff, #fafafa);
            border-radius: 0 8px 8px 0;
            font-style: italic;
            color: #6b7280;
        }

        .rich-content blockquote p {
            margin: 0;
        }

        /* ─── Lists ─── */
        .rich-content ul {
            list-style: disc;
            padding-left: 1.75em;
            margin: 0.75em 0;
        }

        .rich-content ol {
            list-style: decimal;
            padding-left: 1.75em;
            margin: 0.75em 0;
        }

        .rich-content li {
            margin: 0.35em 0;
        }

        .rich-content li>ul,
        .rich-content li>ol {
            margin: 0.2em 0;
        }

        /* Task list */
        .rich-content ul[data-type="taskList"] {
            list-style: none;
            padding-left: 0.25em;
        }

        .rich-content ul[data-type="taskList"] li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 3px 0;
        }

        .rich-content ul[data-type="taskList"] li>label {
            display: flex;
            align-items: center;
            margin-top: 2px;
        }

        .rich-content ul[data-type="taskList"] li>label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #667eea;
            cursor: pointer;
        }

        .rich-content ul[data-type="taskList"] li[data-checked="true"]>div {
            text-decoration: line-through;
            color: #9ca3af;
        }

        /* ─── Horizontal rule ─── */
        .rich-content hr {
            border: none;
            border-top: 2px solid #e5e7eb;
            margin: 2em 0;
        }

        /* ─── Images ─── */
        .rich-content img,
        .rich-content .editor-image {
            max-width: 100%;
            border-radius: 10px;
            margin: 1em 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: block;
        }

        /* ─── Table ─── */
        .rich-content table {
            border-collapse: collapse;
            width: 100%;
            margin: 1.2em 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.07);
        }

        .rich-content table th {
            background: #f8fafc;
            font-weight: 700;
            color: #1f2937;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .rich-content table td {
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            color: #374151;
            vertical-align: top;
        }

        .rich-content table tr:nth-child(even) td {
            background: #fafafa;
        }

        .rich-content table tr:hover td {
            background: #f0f4ff;
        }

        /* ─── YouTube embed ─── */
        .rich-content div[data-youtube-video] iframe,
        .rich-content iframe[src*="youtube"] {
            border-radius: 10px;
            max-width: 100%;
            margin: 1em 0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .empty-note {
            color: #9ca3af;
            font-style: italic;
        }

        /* ─── Attachments ─── */
        .attachments-section {
            margin-bottom: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid #f1f3f9;
        }

        .attachments-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.125rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1rem;
        }

        .attachments-icon {
            width: 20px;
            height: 20px;
        }

        .attachments-list {
            display: grid;
            gap: 0.875rem;
        }

        .attachment-item {
            display: flex;
            gap: 0.875rem;
            padding: 1rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .attachment-item:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .attachment-preview {
            width: 70px;
            height: 70px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .attachment-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .file-icon-large {
            font-size: 2.25rem;
        }

        .attachment-details {
            flex: 1;
            min-width: 0;
        }

        .attachment-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
            word-break: break-word;
            font-size: 0.9375rem;
        }

        .attachment-meta {
            font-size: 0.8125rem;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .attachment-actions {
            display: flex;
            gap: 0.75rem;
        }

        .att-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8125rem;
            font-weight: 500;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 6px;
            transition: all 0.15s;
            border: none;
            cursor: pointer;
        }

        .att-action-btn svg {
            width: 13px;
            height: 13px;
        }

        .download-btn {
            color: #2563eb;
            background: #dbeafe;
        }

        .download-btn:hover {
            background: #3b82f6;
            color: white;
        }

        .delete-btn {
            color: #dc2626;
            background: #fee2e2;
        }

        .delete-btn:hover {
            background: #ef4444;
            color: white;
        }

        /* ─── Footer ─── */
        .note-footer {
            padding-top: 1.5rem;
            border-top: 2px solid #f1f3f9;
        }

        .note-actions {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            border: 1.5px solid;
            background: white;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }

        .action-btn svg {
            width: 15px;
            height: 15px;
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

        /* ─── Responsive ─── */
        @media (max-width: 768px) {
            .note-article {
                padding: 0.5rem 0;
            }

            .note-container {
                padding: 1.25rem 1rem;
                border-radius: 8px;
            }

            .note-title {
                font-size: 1.5rem;
            }

            .rich-content {
                font-size: 1rem;
            }

            .note-actions {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
            }

            .rich-content pre {
                margin: 1em -0rem;
                /* let code blocks breathe */
                border-radius: 8px;
            }

            .rich-content pre code {
                font-size: 0.78em;
                padding: 12px 14px;
            }

            .code-block-header {
                padding: 6px 10px;
            }

            .copy-code-btn {
                font-size: 11px;
                padding: 3px 8px;
            }
        }
    </style>
@endsection
