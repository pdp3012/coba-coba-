@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                        <p class="mt-2 text-sm text-gray-700">
                            Overview of complaints, users, and system statistics.
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none flex space-x-3">
                        <a href="{{ route('admin.complaints') }}" 
                           class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                            Manage Complaints
                        </a>
                        <a href="{{ route('admin.users') }}" 
                           class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                            Manage Users
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
                            <a href="{{ route('admin.complaints') }}" class="font-medium text-primary-700 hover:text-primary-900 transition-colors">
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
                            <a href="{{ route('admin.complaints', ['status' => 'Pending']) }}" class="font-medium text-primary-700 hover:text-primary-900 transition-colors">
                                Review pending
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
                            <a href="{{ route('admin.complaints', ['status' => 'In Progress']) }}" class="font-medium text-primary-700 hover:text-primary-900 transition-colors">
                                View in progress
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ $totalUsers }}</div>
                                        @if($newUsersThisMonth > 0)
                                            <p class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                                +{{ $newUsersThisMonth }} this month
                                            </p>
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('admin.users') }}" class="font-medium text-primary-700 hover:text-primary-900 transition-colors">
                                Manage users
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
                                <a href="{{ route('admin.complaints') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500 transition-colors">
                                    View all →
                                </a>
                            </div>

                            @if($recentComplaints->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recentComplaints->take(5) as $complaint)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-3 mb-2">
                                                        <h4 class="text-sm font-medium text-gray-900 truncate">
                                                            <a href="{{ route('admin.complaints.show', $complaint) }}" class="hover:text-primary-600 transition-colors">
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
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            @if($complaint->user)
                                                                {{ $complaint->user->name }}
                                                            @else
                                                                {{ $complaint->guest_name }} (Guest)
                                                            @endif
                                                        </span>
                                                        <span class="inline-flex items-center">
                                                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            {{ $complaint->created_at->diffForHumans() }}
                                                        </span>
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
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No complaints yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        No complaints have been submitted yet.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- High Priority Complaints -->
                    @if($highPriorityComplaints->count() > 0)
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">🚨 High Priority</h3>
                                <div class="space-y-3">
                                    @foreach($highPriorityComplaints as $complaint)
                                        <div class="border-l-4 border-red-400 bg-red-50 p-3">
                                            <div class="flex">
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-sm font-medium text-red-800 truncate">
                                                        <a href="{{ route('admin.complaints.show', $complaint) }}" class="hover:text-red-900">
                                                            {{ $complaint->title }}
                                                        </a>
                                                    </h4>
                                                    <p class="text-sm text-red-700">
                                                        @if($complaint->user)
                                                            by {{ $complaint->user->name }}
                                                        @else
                                                            by {{ $complaint->guest_name }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Complaints by Status -->
                    @if(!empty($complaintsByStatus))
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Status Overview</h3>
                                <div class="space-y-3">
                                    @foreach($complaintsByStatus as $status => $count)
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">{{ $status }}</span>
                                            <div class="flex items-center">
                                                <span class="text-sm font-medium text-gray-900 mr-2">{{ $count }}</span>
                                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                                    @php
                                                        $percentage = $totalComplaints > 0 ? ($count / $totalComplaints) * 100 : 0;
                                                        $color = match($status) {
                                                            'Pending' => 'bg-yellow-500',
                                                            'In Progress' => 'bg-blue-500',
                                                            'Resolved' => 'bg-green-500',
                                                            default => 'bg-gray-500'
                                                        };
                                                    @endphp
                                                    <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>
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
                                <a href="{{ route('admin.complaints') }}" 
                                   class="flex items-center p-3 text-sm font-medium text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                    <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Manage Complaints
                                </a>
                                <a href="{{ route('admin.users') }}" 
                                   class="flex items-center p-3 text-sm font-medium text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                    <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                    Manage Users
                                </a>
                                <a href="{{ route('admin.statistics') }}" 
                                   class="flex items-center p-3 text-sm font-medium text-gray-900 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                                    <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    View Statistics
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection