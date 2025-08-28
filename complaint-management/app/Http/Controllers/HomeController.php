<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index()
    {
        // Get recent complaint statistics for the homepage
        $totalComplaints = Complaint::count();
        $resolvedComplaints = Complaint::where('status', 'Resolved')->count();
        $pendingComplaints = Complaint::where('status', 'Pending')->count();
        
        return view('home', compact(
            'totalComplaints',
            'resolvedComplaints', 
            'pendingComplaints'
        ));
    }
}
