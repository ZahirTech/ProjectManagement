<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectItem;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get project filter value FIRST
        $projectId = $request->get('project_id', 'all');

        // Get stats - using fresh queries each time
        $stats = [
            'total' => ProjectItem::accessibleBy($userId)->count(),
            'pending' => ProjectItem::accessibleBy($userId)->where('status', 'pending')->count(),
            'processing' => ProjectItem::accessibleBy($userId)->where('status', 'processing')->count(),
            'completed' => ProjectItem::accessibleBy($userId)->where('status', 'completed')->count(),
            'hold' => ProjectItem::accessibleBy($userId)->where('status', 'on-hold')->count(),
            'total_notes' => Note::accessibleBy($userId)->count(),
        ];

        // Get last month stats for comparison
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $lastMonthStats = [
            'total' => ProjectItem::accessibleBy($userId)
                ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->count(),
            'pending' => ProjectItem::accessibleBy($userId)
                ->where('status', 'pending')
                ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->count(),
            'processing' => ProjectItem::accessibleBy($userId)
                ->where('status', 'processing')
                ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->count(),
            'completed' => ProjectItem::accessibleBy($userId)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->count(),
        ];

        // Calculate trends
        $trends = [
            'total' => $this->calculateTrend($stats['total'], $lastMonthStats['total']),
            'pending' => $this->calculateTrend($stats['pending'], $lastMonthStats['pending']),
            'processing' => $this->calculateTrend($stats['processing'], $lastMonthStats['processing']),
            'completed' => $this->calculateTrend($stats['completed'], $lastMonthStats['completed']),
        ];

        // Get ALL pinned assignments (no project filter on pinned items)
        $pinnedAssignments = ProjectItem::accessibleBy($userId)
            ->with(['project', 'attachments'])
            ->whereHas('pinnedBy', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->latest('updated_at')
            ->get();

        // Get ALL pinned notes (no project filter on pinned items)
        $pinnedNotes = Note::accessibleBy($userId)
            ->with(['project'])
            ->whereHas('pinnedBy', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->latest('updated_at')
            ->get();

        // Merge and sort all pinned items by updated_at
        $allPinnedItems = collect([])
            ->merge($pinnedAssignments)
            ->merge($pinnedNotes)
            ->sortByDesc('updated_at')
            ->values();

        $totalPinned = $allPinnedItems->count();

        // Build recent updates query - DON'T EXCLUDE PINNED ITEMS
        $recentQuery = ProjectItem::query()
            ->where(function ($query) use ($userId) {
                $query->where('created_by', $userId)
                    ->orWhere('assigned_to', $userId)
                    ->orWhere('is_private', false);
            })
            ->with(['project', 'attachments']);

        // Apply project filter if selected
        if ($projectId && $projectId != 'all') {
            $recentQuery->where('project_id', $projectId);
        }

        // Get 5 most recent items ordered by updated_at
        // REMOVED: whereNotIn for pinned items - they can appear in both sections
        $recentUpdates = $recentQuery
            ->latest('updated_at')
            ->limit(5)
            ->get();

        // Get all active projects for the dropdown
        $projects = Project::where('status', 'active')->get();

        return view('pages.dashboard', compact(
            'stats',
            'trends',
            'allPinnedItems',
            'totalPinned',
            'recentUpdates',
            'projects',
            'projectId'
        ));
    }

    private function calculateTrend($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        $change = (($current - $previous) / $previous) * 100;
        return round($change, 1);
    }
}
