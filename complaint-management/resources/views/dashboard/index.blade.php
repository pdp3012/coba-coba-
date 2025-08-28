@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->name }}!</h1>
                        <p class="mt-2 text-sm text-gray-700">
                            You are a <span class="font-medium {{ $user->title_color }}">{{ $user->title }}</span>
                            with {{ $totalComplaints }} {{ Str::plural('complaint', $totalComplaints) }} submitted.
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                        <a href="{{ route('complaints.create') }}" 
                           class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Submit New Complaint
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <!-- Total Complaints -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Complaints</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $totalComplaints }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('complaints.index') }}" class="font-medium text-primary-700 hover:text-primary-900 transition-colors">
                                View all
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pending Complaints -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pending Review</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $pendingComplaints }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('complaints.index', ['status' => 'Pending']) }}" class="font-medium text-primary-700 hover:text-primary-900 transition-colors">
                                View pending
                            </a>
                        </div>
                    </div>
                </div>

                <!-- In Progress Complaints -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">In Progress</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $inProgressComplaints }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('complaints.index', ['status' => 'In Progress']) }}" class="font-medium text-primary-700 hover:text-primary-900 transition-colors">
                                View in progress
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Resolved Complaints -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Resolved</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $resolvedComplaints }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('complaints.index', ['status' => 'Resolved']) }}" class="font-medium text-primary-700 hover:text-primary-900 transition-colors">
                                View resolved
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Complaints -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Complaints</h3>
                                <a href="{{ route('complaints.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500 transition-colors">
                                    View all →
                                </a>
                            </div>

                            @if($recentComplaints->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentComplaints as $complaint)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-3 mb-2">
                                                        <h4 class="text-sm font-medium text-gray-900 truncate">
                                                            <a href="{{ route('complaints.show', $complaint) }}" class="hover:text-primary-600 transition-colors">
                                                                {{ $complaint->title }}
                                                            </a>
                                                        </h4>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $complaint->status_color }}">
                                                            {{ $complaint->status }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-500 line-clamp-2">
                                                        {{ Str::limit($complaint->description, 100) }}
                                                    </p>
                                                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                                        <span class="inline-flex items-center">
                                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                            </svg>
                                                            {{ $complaint->category }}
                                                        </span>
                                                        <span class="inline-flex items-center">
                                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            {{ $complaint->created_at->diffForHumans() }}
                                                        </span>
                                                        @if($complaint->attachments->count() > 0)
                                                            <span class="inline-flex items-center">
                                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                                </svg>
                                                                {{ $complaint->attachments->count() }} {{ Str::plural('file', $complaint->attachments->count()) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex-shrink-0">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $complaint->priority_color }}">
                                                        {{ $complaint->priority }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No complaints yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Get started by submitting your first complaint.
                                    </p>
                                    <div class="mt-6">
                                        <a href="{{ route('complaints.create') }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Submit Complaint
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- User Title Progress -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Title Progress</h3>
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full {{ $user->title === 'Veteran Complainer' ? 'bg-purple-100' : ($user->title === 'Active Contributor' ? 'bg-blue-100' : 'bg-green-100') }}">
                                    @if($user->title === 'Veteran Complainer')
                                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                    @elseif($user->title === 'Active Contributor')
                                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    @else
                                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <h4 class="mt-3 text-lg font-medium {{ $user->title_color }}">{{ $user->title }}</h4>
                                <div class="mt-2 text-sm text-gray-500">
                                    @if($user->title === 'Newcomer')
                                        <p>Submit {{ 4 - $totalComplaints }} more complaints to become an Active Contributor</p>
                                        <div class="mt-2 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(($totalComplaints / 4) * 100, 100) }}%"></div>
                                        </div>
                                    @elseif($user->title === 'Active Contributor')
                                        <p>Submit {{ 10 - $totalComplaints }} more complaints to become a Veteran Complainer</p>
                                        <div class="mt-2 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min((($totalComplaints - 4) / 6) * 100, 100) }}%"></div>
                                        </div>
                                    @else
                                        <p>You've reached the highest title! 🎉</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complaints by Category -->
                    @if(!empty($complaintsByCategory))
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Complaints by Category</h3>
                                <div class="space-y-3">
                                    @foreach($complaintsByCategory as $category => $count)
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">{{ $category }}</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('complaints.create') }}" 
                                   class="flex items-center p-3 text-sm font-medium text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                    <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Submit New Complaint
                                </a>
                                <a href="{{ route('complaints.index') }}" 
                                   class="flex items-center p-3 text-sm font-medium text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                    <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    View All Complaints
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection