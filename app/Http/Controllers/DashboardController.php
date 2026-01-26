<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get accessible items for current user
        $accessibleItems = ProjectItem::accessibleBy($userId);

        // Get stats
        $stats = [
            'total' => $accessibleItems->count(),
            'pending' => $accessibleItems->where('status', 'pending')->count(),
            'processing' => $accessibleItems->where('status', 'processing')->count(),
            'completed' => $accessibleItems->where('status', 'completed')->count(),
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

        // Get last pinned status (most recent item)
        $lastStatus = ProjectItem::accessibleBy($userId)
            ->with(['project', 'attachments'])
            ->latest('updated_at')
            ->first();

        // Get recent updates
        $recentUpdates = ProjectItem::accessibleBy($userId)
            ->with(['project', 'attachments'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        // Filter by project if requested
        $projectId = $request->get('project_id', 'all');
        if ($projectId != 'all') {
            $recentUpdates = $recentUpdates->where('project_id', $projectId);
        }

        $projects = Project::where('status', 'active')->get();

        return view('pages.dashboard', compact(
            'stats',
            'trends',
            'lastStatus',
            'recentUpdates',
            'projects'
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
