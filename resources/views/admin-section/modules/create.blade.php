@extends('layouts.admin-dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Module</h1>
            <p class="mt-2 text-sm text-gray-600">Add a new module to {{ $branch->name }}</p>
        </div>
        <a href="{{ route('admin.branches.modules.index', $branch) }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Modules
        </a>
    </div>

    <!-- Create Module Form -->
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.branches.modules.store', $branch) }}" method="POST" class="p-8">
                @csrf

                <div class="space-y-6">
                    <!-- Module Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-900">Module Name</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="mt-2 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Module Factor -->
                    <div>
                        <label for="factor" class="block text-sm font-medium text-gray-900">Factor</label>
                        <div class="mt-2 relative rounded-lg">
                            <input type="number" 
                                   name="factor" 
                                   id="factor" 
                                   value="{{ old('factor') }}"
                                   step="0.1"
                                   min="0"
                                   class="block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                                   required>
                        </div>
                        @error('factor')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button type="submit" 
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create Module
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 