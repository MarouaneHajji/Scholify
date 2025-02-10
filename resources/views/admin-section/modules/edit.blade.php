@extends('layouts.admin-dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Module</h1>
            <p class="mt-2 text-sm text-gray-600">Update module information and manage controls</p>
        </div>
        <a href="{{ route('admin.branches.show', $branch) }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Branch
        </a>
    </div>

    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Edit Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Module Details</h2>
                <form action="{{ route('admin.branches.modules.update', [$branch, $module]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Module Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-900">Module Name</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $module->name) }}"
                                   class="mt-2 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Module Factor -->
                        <div>
                            <label for="factor" class="block text-sm font-medium text-gray-900">Factor</label>
                            <input type="number" 
                                   name="factor" 
                                   id="factor" 
                                   value="{{ old('factor', $module->factor) }}"
                                   step="0.01"
                                   min="0"
                                   class="mt-2 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            @error('factor')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                Update Module
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Controls Section -->
        <div class="bg-white p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl">Controls</h2>
                <a href="{{ route('admin.modules.controls.create', $module) }}" 
                   class="px-4 py-2 bg-blue-500 text-white rounded">
                    Add Control
                </a>
            </div>

            <div>
                @forelse($module->controls as $control)
                    <div class="py-4 border-b">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-medium">{{ $control->name }}</h3>
                                <div class="text-sm text-gray-600 mt-1">
                                    <span>Type: {{ ucfirst($control->type) }}</span> |
                                    <span>Factor: {{ $control->factor }}</span> |
                                    <span>Max Grade: {{ $control->max_grade }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.modules.controls.edit', [$module, $control]) }}" 
                                   class="px-3 py-1 border rounded">
                                    Edit
                                </a>
                                
                                <form action="{{ route('admin.modules.controls.destroy', [$module, $control]) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this control?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 border rounded">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <p>No Controls Yet</p>
                        <p class="text-gray-600">Get started by adding your first control to this module.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 