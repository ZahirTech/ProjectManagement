<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'content',
        'is_private',
        'is_pinned',
        'created_by'
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Check if user can view this note
    public function canView($userId)
    {
        if (!$this->is_private) {
            return true;
        }
        return $this->created_by == $userId;
    }

    // Scope for filtering by user access
    public function scopeAccessibleBy($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('is_private', false)
                ->orWhere('created_by', $userId);
        });
    }
}
