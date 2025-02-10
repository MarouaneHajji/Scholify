@extends('layouts.teacher-dashboard')

@section('title', 'Student Grades')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $student->first_name }} {{ $student->last_name }}</h1>
            <p class="text-gray-600">{{ $class->name }} - {{ $class->branch->name }}</p>
        </div>
        <a href="{{ route('teacher-section.grades.class', $class->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-150">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            Back to Class
        </a>
    </div>

    <!-- Grades Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Grades Management
            </h2>
        </div>
        
        <form action="{{ route('teacher-section.grades.store', [$class->id, $student->id]) }}" method="POST" class="p-6">
            @csrf
            
            @foreach($modules as $module)
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $module->name }}</h3>
                    
                    @if($module->controls->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($module->controls as $control)
                                <div class="flex items-center space-x-4">
                                    <label class="w-1/3 text-sm font-medium text-gray-700">{{ $control->name }}</label>
                                    <input type="number" 
                                           name="grades[{{ $control->id }}]" 
                                           value="{{ $grades[$control->id]->grade_value ?? '' }}"
                                           min="0" 
                                           max="{{ $control->max_grade }}" 
                                           step="0.01"
                                           class="w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="text-sm text-gray-500">/ {{ $control->max_grade }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No controls defined for this module.</p>
                    @endif
                </div>
            @endforeach

            <div class="mt-6 flex justify-end">
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150">
                    Save Grades
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 