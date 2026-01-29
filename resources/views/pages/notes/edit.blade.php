@extends('layouts')

@section('content')
    <div id="edit-note" class="page">
        <div class="page-header">
            <h1>Edit Note</h1>
        </div>

        @include('design.includes.alert')

        <div class="form-card">
            <form action="{{ route('notes.update', $note->id) }}" method="POST">
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
                        <textarea name="content" rows="10" placeholder="Enter note content...">{{ old('content', $note->content) }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>
                            <input type="checkbox" name="is_private" value="1"
                                {{ old('is_private', $note->is_private) ? 'checked' : '' }}>
                            Make this note private
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="submit-btn">Update Note</button>
                    <a href="{{ route('notes.show', $note->id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .btn-secondary {
            padding: 12px 24px;
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
