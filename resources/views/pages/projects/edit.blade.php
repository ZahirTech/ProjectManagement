@extends('layouts')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Project</h1>
            <p class="page-description">Update project details</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                ← Back to Projects
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Project Details</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('projects.update', $project->id) }}" method="POST" class="form-grid">
                @csrf
                @method('PUT')

                <!-- Project Name -->
                <div class="form-group full-width">
                    <label for="projectName" class="form-label required">Project Name</label>
                    <input type="text" id="projectName" name="name" class="form-input"
                        value="{{ old('name', $project->name) }}" placeholder="Enter project name" required>
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group full-width">
                    <label for="projectDescription" class="form-label">Description</label>
                    <textarea id="projectDescription" name="description" class="form-textarea" rows="4"
                        placeholder="Enter project description (optional)">{{ old('description', $project->description) }}</textarea>
                    @error('description')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status -->
                <div class="form-group full-width">
                    <label for="projectStatus" class="form-label required">Status</label>
                    <select id="projectStatus" name="status" class="form-select" required>
                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>On Hold
                        </option>
                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>
                            Completed</option>
                        <option value="archived" {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>
                            Archived</option>
                    </select>
                    @error('status')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="form-group full-width" style="margin-top: 20px;">
                    <div class="form-actions">
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Update Project
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('{{ $errors->first() }}', 'error');
            });
        </script>
    @endif
@endsection
