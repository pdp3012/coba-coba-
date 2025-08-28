@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl md:text-6xl">
                    <span class="block">Your Voice</span>
                    <span class="block text-primary-200">Matters Here</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-primary-200 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Submit your complaints easily and track their progress. We're committed to resolving your concerns quickly and efficiently.
                </p>
                <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                    <div class="rounded-md shadow">
                        <a href="{{ route('complaints.create') }}" 
                           class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10 transition-colors">
                            Submit Complaint
                        </a>
                    </div>
                    @guest
                        <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                            <a href="{{ route('login') }}" 
                               class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-500 hover:bg-primary-600 md:py-4 md:text-lg md:px-10 transition-colors">
                                Login to Track
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-white py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                    Our Track Record
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    See how we've been helping resolve complaints
                </p>
            </div>
            <dl class="mt-10 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8">
                <div class="flex flex-col">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">
                        Total Complaints
                    </dt>
                    <dd class="order-1 text-5xl font-bold text-primary-600">
                        {{ $totalComplaints }}
                    </dd>
                </div>
                <div class="flex flex-col mt-10 sm:mt-0">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">
                        Resolved
                    </dt>
                    <dd class="order-1 text-5xl font-bold text-green-600">
                        {{ $resolvedComplaints }}
                    </dd>
                </div>
                <div class="flex flex-col mt-10 sm:mt-0">
                    <dt class="order-2 mt-2 text-lg leading-6 font-medium text-gray-500">
                        Pending Review
                    </dt>
                    <dd class="order-1 text-5xl font-bold text-orange-600">
                        {{ $pendingComplaints }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-gray-50 py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-primary-600 font-semibold tracking-wide uppercase">Features</h2>
                <p class="mt-2 text-3xl leading-8 font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Why Choose Our Platform?
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    We provide a comprehensive solution for managing and resolving complaints efficiently.
                </p>
            </div>

            <div class="mt-10">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Easy Submission</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Submit complaints quickly with our intuitive form. Upload documents and categorize your issues effortlessly.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Real-time Tracking</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Track the status of your complaints in real-time. Get updates when your complaint moves through different stages.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">User Recognition</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Earn titles based on your participation. From Newcomer to Veteran Complainer, your voice grows stronger.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary-500 text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Email Notifications</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500">
                            Stay informed with email notifications when your complaint status changes or when updates are made.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-primary-600">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-white sm:text-4xl">
                <span class="block">Ready to submit your complaint?</span>
                <span class="block">Start the process today.</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-primary-200">
                Join thousands of users who have successfully resolved their concerns through our platform.
            </p>
            <a href="{{ route('complaints.create') }}" 
               class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-primary-50 sm:w-auto transition-colors">
                Get Started Now
            </a>
        </div>
    </div>
@endsection