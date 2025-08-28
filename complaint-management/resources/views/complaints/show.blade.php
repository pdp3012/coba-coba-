@extends('layouts.app')

@section('title', 'Complaint #' . $complaint->id)

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Complaint #{{ $complaint->id }}</h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Submitted on {{ $complaint->created_at->format('M d, Y \a\t g:i A') }}
                                @if($complaint->user)
                                    by {{ $complaint->user->name }}
                                @else
                                    by {{ $complaint->guest_name }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium {{ $complaint->status_color }}">
                                {{ $complaint->status }}
                            </span>
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium {{ $complaint->priority_color }}">
                                {{ $complaint->priority }} Priority
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @auth
                    @if($complaint->user_id === auth()->id())
                        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    Your complaint is currently <strong>{{ strtolower($complaint->status) }}</strong>
                                </div>
                                <div class="flex space-x-3">
                                    @if($complaint->status !== 'Resolved')
                                        <a href="{{ route('complaints.edit', $complaint) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                    @endif
                                    @if($complaint->status === 'Pending')
                                        <form action="{{ route('complaints.destroy', $complaint) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this complaint? This action cannot be undone.')"
                                                    class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Complaint Details -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-6">
                    <div class="space-y-6">
                        <!-- Title and Category -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $complaint->title }}</h2>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="inline-flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    {{ $complaint->category }}
                                </span>
                                <span class="inline-flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $complaint->created_at->diffForHumans() }}
                                </span>
                                @if($complaint->updated_at->diffInMinutes($complaint->created_at) > 5)
                                    <span class="inline-flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Updated {{ $complaint->updated_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Description</h3>
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! nl2br(e($complaint->description)) !!}
                            </div>
                        </div>

                        <!-- Contact Information (for guest complaints) -->
                        @if(!$complaint->user_id)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Contact Information</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $complaint->guest_name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $complaint->guest_email }}</dd>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Attachments -->
                        @if($complaint->attachments->count() > 0)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Attachments</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($complaint->attachments as $attachment)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    @if(str_starts_with($attachment->file_type, 'image/'))
                                                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    @elseif($attachment->file_type === 'application/pdf')
                                                        <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-8 w-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="ml-3 flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $attachment->original_name }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $attachment->formatted_file_size }}
                                                    </p>
                                                </div>
                                                <div class="ml-3">
                                                    <a href="{{ Storage::url($attachment->file_path) }}" 
                                                       target="_blank"
                                                       class="text-primary-600 hover:text-primary-900 transition-colors">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Admin Notes -->
                        @if($complaint->admin_notes)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h3 class="text-lg font-medium text-blue-900 mb-3">Admin Notes</h3>
                                <div class="prose prose-sm max-w-none text-blue-800">
                                    {!! nl2br(e($complaint->admin_notes)) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Assigned To -->
                        @if($complaint->assigned_to)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Assignment</h3>
                                <p class="text-sm text-gray-700">
                                    This complaint has been assigned to: <strong>{{ $complaint->assigned_to }}</strong>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status Timeline (Placeholder for future enhancement) -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Status History</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Complaint submitted and assigned status: <span class="font-medium text-gray-900">{{ $complaint->status }}</span>
                                                </p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                {{ $complaint->created_at->format('M d, Y g:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="mt-6 flex justify-between">
                <a href="{{ auth()->check() ? route('complaints.index') : route('home') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ auth()->check() ? 'Back to My Complaints' : 'Back to Home' }}
                </a>

                @guest
                    <div class="text-sm text-gray-500">
                        <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">Create an account</a>
                        to track and manage your complaints.
                    </div>
                @endguest
            </div>
        </div>
    </div>
@endsection