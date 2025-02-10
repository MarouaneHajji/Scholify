@extends('layouts.admin-dashboard')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $module->name }} Controls</h1>
            <p class="mt-1 text-gray-600">Manage assessment controls and grades</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.branches.modules.show', [$module->branch_id, $module->id]) }}" 
               class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Module
            </a>
            <a href="{{ route('admin.modules.controls.create', $module) }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Control
            </a>
        </div>
    </div>

    <!-- Controls Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($controls as $control)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-200 transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $control->name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2
                                {{ $control->type === 'exam' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                {{ ucfirst($control->type) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.modules.controls.edit', [$module, $control]) }}" 
                               class="group p-2 hover:bg-gray-50 rounded-lg transition-colors duration-150">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            
                            <form action="{{ route('admin.modules.controls.destroy', [$module, $control]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this control?');"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="group p-2 hover:bg-red-50 rounded-lg transition-colors duration-150">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Factor</span>
                            <span class="font-medium text-gray-900">{{ $control->factor }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Maximum Grade</span>
                            <span class="font-medium text-gray-900">{{ $control->max_grade }} points</span>
                        </div>
                        
                       
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-2xl border border-gray-100">
                    <div class="inline-block p-4 bg-gray-50 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Controls Yet</h3>
                    <p class="text-gray-500 mb-6">Get started by adding your first control to this module.</p>
                    <a href="{{ route('admin.modules.controls.create', $module) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add First Control
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection 