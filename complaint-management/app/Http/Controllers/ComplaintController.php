<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Attachment;
use App\Models\User;
use App\Notifications\HighPriorityComplaintSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the user's complaints.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->complaints();

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
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $complaints = $query->latest()->paginate(10)->appends($request->query());

        return view('complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new complaint.
     */
    public function create()
    {
        return view('complaints.create');
    }

    /**
     * Store a newly created complaint in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:Service,Product,Delivery,Billing,Support,Other',
            'priority' => 'required|in:Low,Medium,High',
            'guest_name' => 'required_without:user_id|string|max:255',
            'guest_email' => 'required_without:user_id|email|max:255',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx'
        ]);

        // Create complaint
        $complaint = new Complaint($validated);
        
        if (auth()->check()) {
            $complaint->user_id = auth()->id();
        }
        
        $complaint->save();

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('attachments', $filename, 'public');

                Attachment::create([
                    'complaint_id' => $complaint->id,
                    'original_name' => $originalName,
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Update user title if authenticated
        if (auth()->check()) {
            auth()->user()->updateTitle();
        }

        // Notify admins if this is a high priority complaint
        if ($complaint->priority === 'High') {
            $admins = User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                $admin->notify(new HighPriorityComplaintSubmitted($complaint));
            }
        }

        $message = auth()->check() 
            ? 'Complaint submitted successfully! You can track its progress in your dashboard.'
            : 'Complaint submitted successfully! We have sent a confirmation to your email.';

        return redirect()->route('complaints.show', $complaint)
                         ->with('success', $message);
    }

    /**
     * Display the specified complaint.
     */
    public function show(Complaint $complaint)
    {
        // Allow access if user owns complaint or if guest complaint matches session
        $canView = false;
        
        if (auth()->check() && $complaint->user_id === auth()->id()) {
            $canView = true;
        } elseif (!$complaint->user_id) {
            // For guest complaints, we'll allow viewing for now
            // In production, you might want to implement a token-based system
            $canView = true;
        }

        if (!$canView) {
            abort(403, 'You do not have permission to view this complaint.');
        }

        $complaint->load('attachments');

        return view('complaints.show', compact('complaint'));
    }

    /**
     * Show the form for editing the specified complaint.
     */
    public function edit(Complaint $complaint)
    {
        // Only allow editing if user owns the complaint and it's not resolved
        if ($complaint->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to edit this complaint.');
        }

        if ($complaint->status === 'Resolved') {
            return redirect()->route('complaints.show', $complaint)
                           ->with('error', 'Resolved complaints cannot be edited.');
        }

        $complaint->load('attachments');

        return view('complaints.edit', compact('complaint'));
    }

    /**
     * Update the specified complaint in storage.
     */
    public function update(Request $request, Complaint $complaint)
    {
        // Only allow updating if user owns the complaint and it's not resolved
        if ($complaint->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to edit this complaint.');
        }

        if ($complaint->status === 'Resolved') {
            return redirect()->route('complaints.show', $complaint)
                           ->with('error', 'Resolved complaints cannot be edited.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:Service,Product,Delivery,Billing,Support,Other',
            'priority' => 'required|in:Low,Medium,High',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
            'remove_attachments' => 'nullable|array',
            'remove_attachments.*' => 'exists:attachments,id'
        ]);

        $complaint->update($validated);

        // Handle removing attachments
        if ($request->filled('remove_attachments')) {
            $attachmentsToRemove = Attachment::whereIn('id', $request->remove_attachments)
                                            ->where('complaint_id', $complaint->id)
                                            ->get();

            foreach ($attachmentsToRemove as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
                $attachment->delete();
            }
        }

        // Handle new file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('attachments', $filename, 'public');

                Attachment::create([
                    'complaint_id' => $complaint->id,
                    'original_name' => $originalName,
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('complaints.show', $complaint)
                         ->with('success', 'Complaint updated successfully!');
    }

    /**
     * Remove the specified complaint from storage.
     */
    public function destroy(Complaint $complaint)
    {
        // Only allow deletion if user owns the complaint and it's pending
        if ($complaint->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to delete this complaint.');
        }

        if ($complaint->status !== 'Pending') {
            return redirect()->route('complaints.show', $complaint)
                           ->with('error', 'Only pending complaints can be deleted.');
        }

        // Delete attachments
        foreach ($complaint->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $complaint->delete();

        // Update user title
        auth()->user()->updateTitle();

        return redirect()->route('complaints.index')
                         ->with('success', 'Complaint deleted successfully!');
    }
}
