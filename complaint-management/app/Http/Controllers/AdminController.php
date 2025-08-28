<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use App\Notifications\ComplaintStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Get overall statistics
        $totalComplaints = Complaint::count();
        $pendingComplaints = Complaint::where('status', 'Pending')->count();
        $inProgressComplaints = Complaint::where('status', 'In Progress')->count();
        $resolvedComplaints = Complaint::where('status', 'Resolved')->count();
        
        $totalUsers = User::where('is_admin', false)->count();
        $newUsersThisMonth = User::where('is_admin', false)
                                ->where('created_at', '>=', now()->startOfMonth())
                                ->count();
        
        // Get recent complaints
        $recentComplaints = Complaint::with(['user', 'attachments'])
                                   ->latest()
                                   ->take(10)
                                   ->get();
        
        // Get high priority complaints
        $highPriorityComplaints = Complaint::where('priority', 'High')
                                          ->where('status', '!=', 'Resolved')
                                          ->with(['user', 'attachments'])
                                          ->latest()
                                          ->take(5)
                                          ->get();
        
        // Get complaints by status for chart
        $complaintsByStatus = Complaint::selectRaw('status, COUNT(*) as count')
                                     ->groupBy('status')
                                     ->pluck('count', 'status')
                                     ->toArray();
        
        // Get complaints by category
        $complaintsByCategory = Complaint::selectRaw('category, COUNT(*) as count')
                                        ->groupBy('category')
                                        ->pluck('count', 'category')
                                        ->toArray();
        
        return view('admin.dashboard', compact(
            'totalComplaints',
            'pendingComplaints', 
            'inProgressComplaints',
            'resolvedComplaints',
            'totalUsers',
            'newUsersThisMonth',
            'recentComplaints',
            'highPriorityComplaints',
            'complaintsByStatus',
            'complaintsByCategory'
        ));
    }

    /**
     * Display all complaints for admin management.
     */
    public function complaints(Request $request)
    {
        $query = Complaint::with(['user', 'attachments']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('email', 'like', '%' . $request->search . '%');
                  })
                  ->orWhere('guest_name', 'like', '%' . $request->search . '%')
                  ->orWhere('guest_email', 'like', '%' . $request->search . '%');
            });
        }

        $complaints = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.complaints', compact('complaints'));
    }

    /**
     * Display a specific complaint for admin review.
     */
    public function showComplaint(Complaint $complaint)
    {
        $complaint->load(['user', 'attachments']);
        
        return view('admin.complaint-detail', compact('complaint'));
    }

    /**
     * Update complaint status.
     */
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Resolved',
            'assigned_to' => 'nullable|string|max:255',
        ]);

        $oldStatus = $complaint->status;
        
        $complaint->update([
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
        ]);

        // Send notification to the user if complaint belongs to a registered user
        if ($complaint->user) {
            $complaint->user->notify(new ComplaintStatusChanged($complaint, $oldStatus, $request->status));
        }
        
        return back()->with('success', "Complaint status updated from '{$oldStatus}' to '{$request->status}'. Notification sent to user.");
    }

    /**
     * Add admin notes to a complaint.
     */
    public function addNotes(Request $request, Complaint $complaint)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $complaint->update([
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Admin notes added successfully.');
    }

    /**
     * Display all users for admin management.
     */
    public function users(Request $request)
    {
        $query = User::where('is_admin', false)->withCount('complaints');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('title')) {
            $query->where('title', $request->title);
        }

        $users = $query->latest()->paginate(20)->appends($request->query());

        return view('admin.users', compact('users'));
    }

    /**
     * Display statistics page.
     */
    public function statistics()
    {
        // Monthly complaint statistics
        $monthlyStats = Complaint::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->take(12)
                                ->get();

        // User registration statistics
        $userStats = User::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                        ->where('is_admin', false)
                        ->groupBy('year', 'month')
                        ->orderBy('year', 'desc')
                        ->orderBy('month', 'desc')
                        ->take(12)
                        ->get();

        // Resolution time statistics (simplified)
        $avgResolutionTime = Complaint::where('status', 'Resolved')
                                    ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
                                    ->value('avg_days');

        // Top categories
        $topCategories = Complaint::selectRaw('category, COUNT(*) as count')
                                 ->groupBy('category')
                                 ->orderBy('count', 'desc')
                                 ->take(6)
                                 ->get();

        // User title distribution
        $titleDistribution = User::selectRaw('title, COUNT(*) as count')
                                ->where('is_admin', false)
                                ->groupBy('title')
                                ->get();

        return view('admin.statistics', compact(
            'monthlyStats',
            'userStats',
            'avgResolutionTime',
            'topCategories',
            'titleDistribution'
        ));
    }
}
