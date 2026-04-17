@extends('layouts')

@section('content')
    <div id="edit-note" class="page">
        <div class="page-header">
            <h1>Edit Note</h1>
        </div>

        @include('design.includes.alert')

        <div class="form-card">
            <form id="editNoteForm" action="{{ route('notes.update', $note->id) }}" method="POST">
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

                        {{-- Hidden textarea to store HTML content --}}
                        <textarea name="content" id="contentTextarea" style="display:none;">{{ old('content', $note->content) }}</textarea>

                        {{-- Rich Text Editor --}}
                        <div class="editor-wrapper">
                            <div class="editor-toolbar" id="editorToolbar">
                                <div class="toolbar-group">
                                    <select class="toolbar-select" id="headingSelect" title="Text style">
                                        <option value="paragraph">Paragraph</option>
                                        <option value="h1">Heading 1</option>
                                        <option value="h2">Heading 2</option>
                                        <option value="h3">Heading 3</option>
                                        <option value="h4">Heading 4</option>
                                    </select>
                                </div>

                                <div class="toolbar-divider"></div>

                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" data-action="bold"
                                        title="Bold (Ctrl+B)"><strong>B</strong></button>
                                    <button type="button" class="toolbar-btn" data-action="italic"
                                        title="Italic (Ctrl+I)"><em>I</em></button>
                                    <button type="button" class="toolbar-btn" data-action="underline"
                                        title="Underline"><span style="text-decoration:underline">U</span></button>
                                    <button type="button" class="toolbar-btn" data-action="strike"
                                        title="Strikethrough"><span style="text-decoration:line-through">S</span></button>
                                    <button type="button" class="toolbar-btn" data-action="code" title="Inline Code">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5">
                                            <polyline points="16 18 22 12 16 6" />
                                            <polyline points="8 6 2 12 8 18" />
                                        </svg>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="highlight" title="Highlight">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M4 20h4l10.5-10.5-4-4L4 16v4zm18.7-13.3c.4-.4.4-1 0-1.4l-2-2c-.4-.4-1-.4-1.4 0l-1.8 1.8 3.4 3.4 1.8-1.8z" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="toolbar-divider"></div>

                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" data-action="link" title="Insert Link">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                                        </svg>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="image" title="Insert Image URL">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" />
                                        </svg>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="youtube"
                                        title="Embed YouTube">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M19.59 6.69a4.83 4.83 0 01-3.77-2.7A12.94 12.94 0 0012 3.5a12.94 12.94 0 00-3.82.49 4.83 4.83 0 01-3.77 2.7A4.78 4.78 0 002 11v2a4.78 4.78 0 002.41 4.31 4.83 4.83 0 013.77 2.7 12.94 12.94 0 003.82.49 12.94 12.94 0 003.82-.49 4.83 4.83 0 013.77-2.7A4.78 4.78 0 0022 13v-2a4.78 4.78 0 00-2.41-4.31zM10 15V9l5 3-5 3z" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="toolbar-divider"></div>

                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" data-action="bulletList"
                                        title="Bullet List">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <line x1="8" y1="6" x2="21" y2="6" />
                                            <line x1="8" y1="12" x2="21" y2="12" />
                                            <line x1="8" y1="18" x2="21" y2="18" />
                                            <line x1="3" y1="6" x2="3.01" y2="6" />
                                            <line x1="3" y1="12" x2="3.01" y2="12" />
                                            <line x1="3" y1="18" x2="3.01" y2="18" />
                                        </svg>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="orderedList"
                                        title="Ordered List">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <line x1="10" y1="6" x2="21" y2="6" />
                                            <line x1="10" y1="12" x2="21" y2="12" />
                                            <line x1="10" y1="18" x2="21" y2="18" />
                                            <path d="M4 6h1v4" />
                                            <path d="M4 10h2" />
                                            <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1" />
                                        </svg>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="taskList" title="Checklist">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="9 11 12 14 22 4" />
                                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                                        </svg>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="blockquote"
                                        title="Blockquote">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="toolbar-divider"></div>

                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" data-action="codeBlock"
                                        title="Code Block">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <rect x="2" y="3" width="20" height="14" rx="2" />
                                            <line x1="8" y1="21" x2="16" y2="21" />
                                            <line x1="12" y1="17" x2="12" y2="21" />
                                        </svg>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="table" title="Insert Table">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <line x1="3" y1="9" x2="21" y2="9" />
                                            <line x1="3" y1="15" x2="21" y2="15" />
                                            <line x1="9" y1="3" x2="9" y2="21" />
                                            <line x1="15" y1="3" x2="15" y2="21" />
                                        </svg>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="horizontalRule"
                                        title="Horizontal Rule">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <line x1="5" y1="12" x2="19" y2="12" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="toolbar-divider"></div>

                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" data-action="alignLeft"
                                        title="Align Left">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <line x1="3" y1="6" x2="21" y2="6" />
                                            <line x1="3" y1="12" x2="15" y2="12" />
                                            <line x1="3" y1="18" x2="18" y2="18" />
                                        </svg>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="alignCenter"
                                        title="Align Center">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <line x1="3" y1="6" x2="21" y2="6" />
                                            <line x1="6" y1="12" x2="18" y2="12" />
                                            <line x1="4" y1="18" x2="20" y2="18" />
                                        </svg>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="alignRight"
                                        title="Align Right">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <line x1="3" y1="6" x2="21" y2="6" />
                                            <line x1="9" y1="12" x2="21" y2="12" />
                                            <line x1="6" y1="18" x2="21" y2="18" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="toolbar-divider"></div>

                                <div class="toolbar-group">
                                    <button type="button" class="toolbar-btn" data-action="undo" title="Undo">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="1 4 1 10 7 10" />
                                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10" />
                                        </svg>
                                    </button>
                                    <button type="button" class="toolbar-btn" data-action="redo" title="Redo">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="23 4 23 10 17 10" />
                                            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="code-lang-bar" id="codeLangBar" style="display:none;">
                                <span class="code-lang-label">Language:</span>
                                <select id="codeLangSelect" class="code-lang-select">
                                    <option value="">Auto-detect</option>
                                    <option value="javascript">JavaScript</option>
                                    <option value="typescript">TypeScript</option>
                                    <option value="php">PHP</option>
                                    <option value="python">Python</option>
                                    <option value="java">Java</option>
                                    <option value="csharp">C#</option>
                                    <option value="cpp">C++</option>
                                    <option value="c">C</option>
                                    <option value="go">Go</option>
                                    <option value="rust">Rust</option>
                                    <option value="ruby">Ruby</option>
                                    <option value="swift">Swift</option>
                                    <option value="kotlin">Kotlin</option>
                                    <option value="html">HTML</option>
                                    <option value="css">CSS</option>
                                    <option value="scss">SCSS</option>
                                    <option value="sql">SQL</option>
                                    <option value="bash">Bash / Shell</option>
                                    <option value="json">JSON</option>
                                    <option value="xml">XML</option>
                                    <option value="yaml">YAML</option>
                                    <option value="markdown">Markdown</option>
                                    <option value="plaintext">Plain Text</option>
                                </select>
                            </div>

                            <div id="tiptapEditor" class="tiptap-editor"></div>
                            <div class="editor-footer"><span id="wordCount">0 words · 0 chars</span></div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_private" value="1"
                                {{ old('is_private', $note->is_private) ? 'checked' : '' }}>
                            <span></span>
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
                                            onclick="deleteAttachment({{ $attachment->id }})"
                                            title="Delete attachment">✕</button>
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
                            <div style="font-size: 12px; color: #a0aec0; margin-top: 5px;">PDF, DOC, XLS, Images (Max 300MB
                                each)</div>
                        </div>
                        <div class="uploaded-files-list" id="newFilesList"></div>
                        <button type="button" class="upload-new-files-btn" id="uploadBtn"
                            style="display: none; margin-top: 10px;">Upload Selected Files</button>
                    </div>
                </div>

                <div class="form-footer-actions">
                    <button type="submit" class="submit-btn">Update Note</button>
                    <a href="{{ route('notes.show', $note->id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">

    <script type="module">
        import {
            Editor
        } from 'https://esm.sh/@tiptap/core@2.4.0'
        import StarterKit from 'https://esm.sh/@tiptap/starter-kit@2.4.0'
        import Underline from 'https://esm.sh/@tiptap/extension-underline@2.4.0'
        import Link from 'https://esm.sh/@tiptap/extension-link@2.4.0'
        import Image from 'https://esm.sh/@tiptap/extension-image@2.4.0'
        import TextAlign from 'https://esm.sh/@tiptap/extension-text-align@2.4.0'
        import Highlight from 'https://esm.sh/@tiptap/extension-highlight@2.4.0'
        import TaskList from 'https://esm.sh/@tiptap/extension-task-list@2.4.0'
        import TaskItem from 'https://esm.sh/@tiptap/extension-task-item@2.4.0'
        import Table from 'https://esm.sh/@tiptap/extension-table@2.4.0'
        import TableRow from 'https://esm.sh/@tiptap/extension-table-row@2.4.0'
        import TableCell from 'https://esm.sh/@tiptap/extension-table-cell@2.4.0'
        import TableHeader from 'https://esm.sh/@tiptap/extension-table-header@2.4.0'
        import CodeBlockLowlight from 'https://esm.sh/@tiptap/extension-code-block-lowlight@2.4.0'
        import {
            createLowlight,
            common
        } from 'https://esm.sh/lowlight@3.1.0'
        import Youtube from 'https://esm.sh/@tiptap/extension-youtube@2.4.0'
        import Placeholder from 'https://esm.sh/@tiptap/extension-placeholder@2.4.0'
        import Typography from 'https://esm.sh/@tiptap/extension-typography@2.4.0'
        import CharacterCount from 'https://esm.sh/@tiptap/extension-character-count@2.4.0'

        const lowlight = createLowlight(common)

        // Get existing content from hidden textarea
        const existingContent = document.getElementById('contentTextarea').value

        const editor = new Editor({
            element: document.getElementById('tiptapEditor'),
            extensions: [
                StarterKit.configure({
                    codeBlock: false
                }),
                Underline,
                Link.configure({
                    openOnClick: false,
                    HTMLAttributes: {
                        class: 'editor-link'
                    }
                }),
                Image.configure({
                    HTMLAttributes: {
                        class: 'editor-image'
                    }
                }),
                TextAlign.configure({
                    types: ['heading', 'paragraph']
                }),
                Highlight.configure({
                    multicolor: false
                }),
                TaskList,
                TaskItem.configure({
                    nested: true
                }),
                Table.configure({
                    resizable: true
                }),
                TableRow, TableHeader, TableCell,
                CodeBlockLowlight.configure({
                    lowlight,
                    HTMLAttributes: {
                        class: 'code-block-wrapper'
                    },
                    languageClassPrefix: 'language-'
                }),
                Youtube.configure({
                    controls: true
                }),
                Placeholder.configure({
                    placeholder: 'Start writing your note…'
                }),
                Typography,
                CharacterCount,
            ],
            content: existingContent || '',
            onUpdate({
                editor
            }) {
                document.getElementById('contentTextarea').value = editor.getHTML()
                updateWordCount(editor)
            },
            onCreate({
                editor
            }) {
                updateWordCount(editor)
                setupToolbar(editor)
            },
        })

        function updateWordCount(editor) {
            const chars = editor.storage.characterCount.characters()
            const words = editor.storage.characterCount.words()
            const el = document.getElementById('wordCount')
            if (el) el.textContent = `${words} words · ${chars} chars`
        }

        function setupToolbar(editor) {
            editor.on('selectionUpdate', () => updateActiveStates(editor))
            editor.on('transaction', () => updateActiveStates(editor))
        }

        function updateActiveStates(editor) {
            const checks = {
                bold: 'bold',
                italic: 'italic',
                underline: 'underline',
                strike: 'strike',
                code: 'code',
                highlight: 'highlight',
                bulletList: 'bulletList',
                orderedList: 'orderedList',
                taskList: 'taskList',
                blockquote: 'blockquote',
                codeBlock: 'codeBlock'
            }
            Object.entries(checks).forEach(([action, mark]) => {
                const btn = document.querySelector(`[data-action="${action}"]`)
                if (btn) btn.classList.toggle('is-active', editor.isActive(mark))
            })
            const alignments = {
                alignLeft: 'left',
                alignCenter: 'center',
                alignRight: 'right'
            }
            Object.entries(alignments).forEach(([action, align]) => {
                const btn = document.querySelector(`[data-action="${action}"]`)
                if (btn) btn.classList.toggle('is-active', editor.isActive({
                    textAlign: align
                }))
            })
            const headingSelect = document.getElementById('headingSelect')
            if (headingSelect) {
                headingSelect.value = 'paragraph'
                for (let i = 1; i <= 4; i++) {
                    if (editor.isActive('heading', {
                            level: i
                        })) {
                        headingSelect.value = `h${i}`;
                        break
                    }
                }
            }
            const codeLangBar = document.getElementById('codeLangBar')
            codeLangBar.style.display = editor.isActive('codeBlock') ? 'flex' : 'none'
        }

        document.querySelectorAll('.toolbar-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.dataset.action
                switch (action) {
                    case 'bold':
                        editor.chain().focus().toggleBold().run();
                        break
                    case 'italic':
                        editor.chain().focus().toggleItalic().run();
                        break
                    case 'underline':
                        editor.chain().focus().toggleUnderline().run();
                        break
                    case 'strike':
                        editor.chain().focus().toggleStrike().run();
                        break
                    case 'code':
                        editor.chain().focus().toggleCode().run();
                        break
                    case 'highlight':
                        editor.chain().focus().toggleHighlight().run();
                        break
                    case 'bulletList':
                        editor.chain().focus().toggleBulletList().run();
                        break
                    case 'orderedList':
                        editor.chain().focus().toggleOrderedList().run();
                        break
                    case 'taskList':
                        editor.chain().focus().toggleTaskList().run();
                        break
                    case 'blockquote':
                        editor.chain().focus().toggleBlockquote().run();
                        break
                    case 'codeBlock':
                        editor.chain().focus().toggleCodeBlock().run();
                        break
                    case 'horizontalRule':
                        editor.chain().focus().setHorizontalRule().run();
                        break
                    case 'alignLeft':
                        editor.chain().focus().setTextAlign('left').run();
                        break
                    case 'alignCenter':
                        editor.chain().focus().setTextAlign('center').run();
                        break
                    case 'alignRight':
                        editor.chain().focus().setTextAlign('right').run();
                        break
                    case 'undo':
                        editor.chain().focus().undo().run();
                        break
                    case 'redo':
                        editor.chain().focus().redo().run();
                        break
                    case 'link': {
                        const prev = editor.getAttributes('link').href
                        const url = window.prompt('Enter URL:', prev || 'https://')
                        if (url === null) return
                        if (url === '') {
                            editor.chain().focus().unsetLink().run();
                            return
                        }
                        editor.chain().focus().setLink({
                            href: url
                        }).run();
                        break
                    }
                    case 'image': {
                        const url = window.prompt('Enter image URL:')
                        if (url) editor.chain().focus().setImage({
                            src: url
                        }).run();
                        break
                    }
                    case 'youtube': {
                        const url = window.prompt('Enter YouTube URL:')
                        if (url) editor.chain().focus().setYoutubeVideo({
                            src: url
                        }).run();
                        break
                    }
                    case 'table':
                        editor.chain().focus().insertTable({
                            rows: 3,
                            cols: 3,
                            withHeaderRow: true
                        }).run();
                        break
                }
            })
        })

        document.getElementById('headingSelect')?.addEventListener('change', function() {
            const val = this.value
            if (val === 'paragraph') editor.chain().focus().setParagraph().run()
            else editor.chain().focus().toggleHeading({
                level: parseInt(val.replace('h', ''))
            }).run()
        })

        document.getElementById('codeLangSelect')?.addEventListener('change', function() {
            editor.chain().focus().setCodeBlock({
                language: this.value
            }).run()
        })

        document.getElementById('editNoteForm').addEventListener('submit', function() {
            document.getElementById('contentTextarea').value = editor.getHTML()
        })
    </script>

    <script>
        // Attachment upload/delete
        function displayNewFiles(files) {
            const filesList = document.getElementById('newFilesList')
            const uploadBtn = document.getElementById('uploadBtn')
            filesList.innerHTML = ''
            if (!files || files.length === 0) {
                uploadBtn.style.display = 'none';
                return
            }
            uploadBtn.style.display = 'block'
            Array.from(files).forEach((file, index) => {
                let icon = file.type.startsWith('image/') ? '🖼️' : file.type.includes('pdf') ? '📄' : file.name
                    .endsWith('.docx') || file.name.endsWith('.doc') ? '📝' : file.name.endsWith('.xlsx') || file
                    .name.endsWith('.xls') ? '📊' : '📎'
                const el = document.createElement('div')
                el.className = 'file-item'
                el.innerHTML =
                    `<div class="file-item-content"><span class="file-item-icon">${icon}</span><div class="file-item-info"><div class="file-item-name">${file.name}</div><div class="file-item-size">${(file.size/1024/1024).toFixed(2)} MB</div></div></div><button type="button" class="file-remove-btn" onclick="removeNewFile(${index})">✕</button>`
                filesList.appendChild(el)
            })
        }

        function removeNewFile(index) {
            const fi = document.getElementById('newFileInput')
            const dt = new DataTransfer()
            const files = Array.from(fi.files);
            files.splice(index, 1)
            files.forEach(f => dt.items.add(f))
            fi.files = dt.files;
            displayNewFiles(fi.files)
        }

        function uploadNewFiles() {
            const fi = document.getElementById('newFileInput')
            if (!fi.files || fi.files.length === 0) {
                alert('Please select files to upload');
                return
            }
            const btn = document.getElementById('uploadBtn');
            btn.disabled = true;
            btn.textContent = 'Uploading...'
            const fd = new FormData()
            Array.from(fi.files).forEach(f => fd.append('files[]', f))
            fetch('{{ route('notes.attachments.upload', $note->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: fd
                })
                .then(r => r.json()).then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload()
                    } else {
                        alert('Upload failed: ' + (data.message || 'Unknown error'));
                        btn.disabled = false;
                        btn.textContent = 'Upload Selected Files'
                    }
                }).catch(() => {
                    alert('Upload failed.');
                    btn.disabled = false;
                    btn.textContent = 'Upload Selected Files'
                })
        }

        function deleteAttachment(id) {
            if (!confirm('Delete this attachment?')) return
            fetch(`/notes/attachments/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json()).then(data => {
                    if (data.success) document.getElementById(`attachment-${id}`).remove()
                    else alert(data.message || 'Delete failed')
                })
        }

        document.addEventListener('DOMContentLoaded', function() {
            const fi = document.getElementById('newFileInput')
            document.getElementById('uploadBtn').addEventListener('click', uploadNewFiles)
            fi.addEventListener('change', function(e) {
                const invalid = Array.from(e.target.files).filter(f => f.size > 300 * 1024 * 1024)
                if (invalid.length) {
                    alert(`Files exceed 300MB:\n${invalid.map(f=>f.name).join('\n')}`);
                    fi.value = '';
                    return
                }
                displayNewFiles(e.target.files)
            })

            const ua = document.querySelector('.file-upload-area');
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e => ua.addEventListener(e, ev => {
                ev.preventDefault();
                ev.stopPropagation()
            }, false));
            ['dragenter', 'dragover'].forEach(e => ua.addEventListener(e, () => ua.classList.add('drag-over')));
            ['dragleave', 'drop'].forEach(e => ua.addEventListener(e, () => ua.classList.remove('drag-over')))
            ua.addEventListener('drop', function(e) {
                const dropped = Array.from(e.dataTransfer.files)
                const invalid = dropped.filter(f => f.size > 300 * 1024 * 1024)
                if (invalid.length) {
                    alert(`Files exceed 300MB:\n${invalid.map(f=>f.name).join('\n')}`);
                    return
                }
                const dt = new DataTransfer();
                [...Array.from(fi.files), ...dropped].forEach(f => dt.items.add(f))
                fi.files = dt.files;
                displayNewFiles(fi.files)
            })
        })
    </script>

    <style>
        .editor-wrapper {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            transition: border-color 0.2s;
        }

        .editor-wrapper:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12);
        }

        .editor-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 2px;
            padding: 8px 10px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 1px;
        }

        .toolbar-divider {
            width: 1px;
            height: 22px;
            background: #e2e8f0;
            margin: 0 4px;
        }

        .toolbar-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 5px;
            background: transparent;
            color: #4a5568;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.15s;
        }

        .toolbar-btn:hover {
            background: #e2e8f0;
            color: #1a202c;
        }

        .toolbar-btn.is-active {
            background: #667eea;
            color: #fff;
        }

        .toolbar-select {
            height: 30px;
            padding: 0 8px;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            background: #fff;
            font-size: 13px;
            color: #4a5568;
            cursor: pointer;
        }

        .code-lang-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            background: #1e1e2e;
            border-bottom: 1px solid #2d2d3f;
        }

        .code-lang-label {
            font-size: 12px;
            color: #a0aec0;
            font-family: monospace;
        }

        .code-lang-select {
            height: 26px;
            padding: 0 8px;
            border: 1px solid #3d3d5c;
            border-radius: 4px;
            background: #2d2d3f;
            color: #e2e8f0;
            font-size: 12px;
            font-family: monospace;
        }

        .tiptap-editor {
            min-height: 320px;
        }

        .tiptap-editor .tiptap {
            padding: 20px 24px;
            min-height: 320px;
            outline: none;
            font-size: 15px;
            line-height: 1.75;
            color: #2d3748;
            font-family: 'Georgia', serif;
        }

        .tiptap-editor .tiptap p.is-editor-empty:first-child::before {
            content: attr(data-placeholder);
            float: left;
            color: #a0aec0;
            pointer-events: none;
            height: 0;
        }

        .tiptap-editor .tiptap h1 {
            font-size: 2em;
            font-weight: 700;
            margin: 1em 0 0.4em;
            color: #1a202c;
        }

        .tiptap-editor .tiptap h2 {
            font-size: 1.5em;
            font-weight: 700;
            margin: 0.9em 0 0.4em;
            color: #1a202c;
        }

        .tiptap-editor .tiptap h3 {
            font-size: 1.25em;
            font-weight: 600;
            margin: 0.8em 0 0.4em;
            color: #1a202c;
        }

        .tiptap-editor .tiptap h4 {
            font-size: 1.1em;
            font-weight: 600;
            margin: 0.7em 0 0.3em;
            color: #1a202c;
        }

        .tiptap-editor .tiptap p {
            margin: 0 0 0.75em;
        }

        .tiptap-editor .tiptap a {
            color: #667eea;
            text-decoration: underline;
        }

        .tiptap-editor .tiptap mark {
            background: #fef08a;
            padding: 0 2px;
            border-radius: 2px;
        }

        .tiptap-editor .tiptap code {
            background: #f1f5f9;
            color: #e53e3e;
            padding: 1px 5px;
            border-radius: 4px;
            font-family: 'Fira Code', 'Consolas', monospace;
            font-size: 0.875em;
        }

        .tiptap-editor .tiptap pre {
            background: #1e1e2e;
            color: #cdd6f4;
            border-radius: 8px;
            padding: 0;
            margin: 1.2em 0;
            overflow: hidden;
            font-family: 'Fira Code', 'Consolas', monospace;
            font-size: 0.875em;
            position: relative;
        }

        .tiptap-editor .tiptap pre::before {
            content: attr(data-language);
            display: block;
            background: #2d2d3f;
            color: #89b4fa;
            font-size: 11px;
            padding: 6px 14px;
            font-family: monospace;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-bottom: 1px solid #3d3d5c;
        }

        .tiptap-editor .tiptap pre code {
            display: block;
            background: none;
            color: inherit;
            padding: 14px 16px;
            border-radius: 0;
            font-size: inherit;
            overflow-x: auto;
            white-space: pre;
        }

        .tiptap-editor .tiptap blockquote {
            border-left: 4px solid #667eea;
            padding: 8px 16px;
            margin: 1em 0;
            color: #718096;
            font-style: italic;
            background: #f8f7ff;
            border-radius: 0 6px 6px 0;
        }

        .tiptap-editor .tiptap ul,
        .tiptap-editor .tiptap ol {
            padding-left: 1.5em;
            margin: 0.5em 0 0.75em;
        }

        .tiptap-editor .tiptap ul[data-type="taskList"] {
            list-style: none;
            padding-left: 0.5em;
        }

        .tiptap-editor .tiptap ul[data-type="taskList"] li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .tiptap-editor .tiptap ul[data-type="taskList"] li>label {
            margin-top: 3px;
            cursor: pointer;
        }

        .tiptap-editor .tiptap ul[data-type="taskList"] li[data-checked="true"]>div {
            text-decoration: line-through;
            color: #a0aec0;
        }

        .tiptap-editor .tiptap hr {
            border: none;
            border-top: 2px solid #e2e8f0;
            margin: 1.5em 0;
        }

        .tiptap-editor .tiptap img {
            max-width: 100%;
            border-radius: 8px;
            margin: 0.5em 0;
        }

        .tiptap-editor .tiptap table {
            border-collapse: collapse;
            width: 100%;
            margin: 1em 0;
        }

        .tiptap-editor .tiptap table th,
        .tiptap-editor .tiptap table td {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            text-align: left;
        }

        .tiptap-editor .tiptap table th {
            background: #f7fafc;
            font-weight: 600;
        }

        .editor-footer {
            padding: 6px 14px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 12px;
            color: #a0aec0;
            text-align: right;
        }

        .form-footer-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 24px;
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

        .file-upload-area {
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .file-upload-area:hover,
        .file-upload-area.drag-over {
            border-color: #4299e1;
            background: #ebf8ff;
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
        }
    </style>
@endsection
