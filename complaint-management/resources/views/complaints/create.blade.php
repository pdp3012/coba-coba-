@extends('layouts.app')

@section('title', 'Submit Complaint')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Submit a Complaint</h1>
                        <p class="mt-1 text-sm text-gray-600">
                            Please provide detailed information about your complaint. All fields marked with * are required.
                        </p>
                    </div>

                    <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        @guest
                            <!-- Guest Information -->
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                <h3 class="text-lg font-medium text-blue-900 mb-3">Your Contact Information</h3>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="guest_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                                        <input type="text" 
                                               name="guest_name" 
                                               id="guest_name" 
                                               value="{{ old('guest_name') }}"
                                               required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('guest_name') border-red-300 @enderror">
                                        @error('guest_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="guest_email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                                        <input type="email" 
                                               name="guest_email" 
                                               id="guest_email" 
                                               value="{{ old('guest_email') }}"
                                               required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('guest_email') border-red-300 @enderror">
                                        @error('guest_email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mt-3 text-sm text-blue-700">
                                    <p>📌 <a href="{{ route('register') }}" class="font-medium underline">Create an account</a> to track your complaints and earn community titles!</p>
                                </div>
                            </div>
                        @endguest

                        <!-- Complaint Details -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Complaint Title *</label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title') }}"
                                   required
                                   placeholder="Brief description of your complaint"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('title') border-red-300 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                                <select name="category" 
                                        id="category" 
                                        required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('category') border-red-300 @enderror">
                                    <option value="">Select a category</option>
                                    <option value="Service" {{ old('category') === 'Service' ? 'selected' : '' }}>Service</option>
                                    <option value="Product" {{ old('category') === 'Product' ? 'selected' : '' }}>Product</option>
                                    <option value="Delivery" {{ old('category') === 'Delivery' ? 'selected' : '' }}>Delivery</option>
                                    <option value="Billing" {{ old('category') === 'Billing' ? 'selected' : '' }}>Billing</option>
                                    <option value="Support" {{ old('category') === 'Support' ? 'selected' : '' }}>Support</option>
                                    <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">Priority *</label>
                                <select name="priority" 
                                        id="priority" 
                                        required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('priority') border-red-300 @enderror">
                                    <option value="">Select priority</option>
                                    <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                                    <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Detailed Description *</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="6" 
                                      required
                                      placeholder="Please provide a detailed description of your complaint, including any relevant details, dates, and outcomes you're seeking."
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Attachments -->
                        <div>
                            <label for="attachments" class="block text-sm font-medium text-gray-700">Attachments (Optional)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="attachments" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span>Upload files</span>
                                            <input id="attachments" 
                                                   name="attachments[]" 
                                                   type="file" 
                                                   multiple 
                                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                   class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, PDF, DOC up to 10MB each
                                    </p>
                                </div>
                            </div>
                            @error('attachments.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- File preview area -->
                            <div id="file-preview" class="mt-4 hidden">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Selected files:</h4>
                                <div id="file-list" class="space-y-2"></div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <div class="text-sm text-gray-500">
                                * Required fields
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('home') }}" 
                                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    Submit Complaint
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File upload preview
        document.getElementById('attachments').addEventListener('change', function(e) {
            const filePreview = document.getElementById('file-preview');
            const fileList = document.getElementById('file-list');
            const files = e.target.files;

            if (files.length > 0) {
                filePreview.classList.remove('hidden');
                fileList.innerHTML = '';

                Array.from(files).forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-md';
                    fileItem.innerHTML = `
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm text-gray-900">${file.name}</span>
                            <span class="text-xs text-gray-500 ml-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                        </div>
                    `;
                    fileList.appendChild(fileItem);
                });
            } else {
                filePreview.classList.add('hidden');
            }
        });

        // Auto-select Medium priority if none selected
        document.addEventListener('DOMContentLoaded', function() {
            const prioritySelect = document.getElementById('priority');
            if (prioritySelect.value === '') {
                prioritySelect.value = 'Medium';
            }
        });
    </script>
@endsection