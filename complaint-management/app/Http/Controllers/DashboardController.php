<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get user's complaint statistics
        $totalComplaints = $user->complaints()->count();
        $pendingComplaints = $user->complaints()->where('status', 'Pending')->count();
        $inProgressComplaints = $user->complaints()->where('status', 'In Progress')->count();
        $resolvedComplaints = $user->complaints()->where('status', 'Resolved')->count();
        
        // Get recent complaints
        $recentComplaints = $user->complaints()
                                 ->with('attachments')
                                 ->latest()
                                 ->take(5)
                                 ->get();
        
        // Get complaint breakdown by category
        $complaintsByCategory = $user->complaints()
                                    ->selectRaw('category, COUNT(*) as count')
                                    ->groupBy('category')
                                    ->pluck('count', 'category')
                                    ->toArray();
        
        // Get complaint breakdown by priority
        $complaintsByPriority = $user->complaints()
                                    ->selectRaw('priority, COUNT(*) as count')
                                    ->groupBy('priority')
                                    ->pluck('count', 'priority')
                                    ->toArray();
        
        return view('dashboard.index', compact(
            'user',
            'totalComplaints',
            'pendingComplaints',
            'inProgressComplaints',
            'resolvedComplaints',
            'recentComplaints',
            'complaintsByCategory',
            'complaintsByPriority'
        ));
    }
}
