@extends('layouts.admin-dashboard')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $branch->name }}</h1>
            <p class="mt-1 text-gray-600">Branch Overview</p>
        </div>
        <a href="{{ route('admin.branches.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Branches
        </a>
    </div>

    <!-- Classes Count Card (previously Students) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-blue-200 transition-colors duration-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-1">
            <div class="p-2 bg-blue-50 rounded-xl">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="text-sm font-medium text-gray-500">Classes</span>
        </div>
        <div class="flex items-baseline">
            <p class="text-3xl font-bold text-gray-900">{{ $branch->classes->count() }}</p>
            <span class="ml-2 text-sm text-gray-600">active</span>
        </div>
    </div>

    <!-- Modules Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Modules</h2>
            <a href="{{ route('admin.branches.modules.create', $branch) }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Module
            </a>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($branch->modules as $module)
                <div x-data="{ open: false }" class="p-6 hover:bg-gray-50/50 transition-all duration-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-grow">
                            <button @click="open = !open" class="flex items-center gap-3 hover:text-indigo-600 transition-colors duration-150">
                                <h3 class="text-lg font-medium text-gray-900">{{ $module->name }}</h3>
                                <svg class="w-5 h-5 transition-transform duration-200" 
                                     :class="{'rotate-180': open}"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <p class="text-sm text-gray-500 mt-1">Factor: {{ $module->factor }}</p>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.branches.modules.show', [$branch->id, $module->id]) }}" 
                               class="group inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 hover:border-gray-300 shadow-sm transition-all duration-150">
                                <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span class="text-sm font-medium">View Details</span>
                            </a>
                            
                            <form action="{{ route('admin.branches.modules.destroy', [$branch->id, $module->id]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this module?');"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="group inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-red-50 hover:border-red-200 hover:text-red-600 shadow-sm transition-all duration-150">
                                    <svg class="w-4 h-4 mr-1.5 group-hover:scale-110 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span class="text-sm font-medium">Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Collapsible content -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="mt-4">
                        
                        <!-- Controls section -->
                        @if($module->controls->isNotEmpty())
                            <div class="bg-white rounded-lg border border-gray-100 p-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-sm font-medium text-gray-700">Controls</h4>
                                    <a href="{{ route('admin.modules.controls.create', $module) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Add Control
                                    </a>
                                </div>
                                
                                <div class="space-y-4">
                                    @foreach($module->controls as $control)
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <h5 class="font-medium text-gray-900">{{ $control->name }}</h5>
                                                <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                                                    <span class="px-2 py-0.5 text-xs rounded-full 
                                                        {{ $control->type === 'exam' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                                        {{ ucfirst($control->type) }}
                                                    </span>
                                                    <span>Factor: {{ $control->factor }}</span>
                                                    <span>Max Grade: {{ $control->max_grade }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.modules.controls.edit', [$module, $control]) }}" 
                                                   class="p-1.5 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6 bg-gray-50 rounded-lg">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="text-sm font-medium text-gray-900">No Controls Yet</h3>
                                <p class="text-sm text-gray-500 mt-1">Get started by adding a control to this module.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="inline-block p-4 bg-gray-50 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Modules Found</h3>
                    <p class="text-gray-500 mb-6">Get started by adding your first module to this branch.</p>
                    <a href="{{ route('admin.branches.modules.create', $branch) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add First Module
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Add any custom styles here */
    .hover-trigger .hover-target {
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
    }
    
    .hover-trigger:hover .hover-target {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script>
    // Add any JavaScript for interactivity here
</script>
@endpush
@endsection 