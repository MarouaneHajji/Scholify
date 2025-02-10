@extends('layouts.admin-dashboard')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Control</h1>
            <p class="mt-1 text-gray-600">Update control information and settings</p>
        </div>
        <a href="{{ route('admin.branches.modules.show', [$module->branch_id, $module->id]) }}" 
           class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Module
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.modules.controls.update', [$module, $control]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Control Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Control Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $control->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('name') border-red-300 @enderror"
                           placeholder="Enter control name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Control Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" 
                            id="type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('type') border-red-300 @enderror">
                        <option value="">Select type</option>
                        <option value="exam" {{ old('type', $control->type) === 'exam' ? 'selected' : '' }}>Exam</option>
                        <option value="test" {{ old('type', $control->type) === 'test' ? 'selected' : '' }}>Test</option>
                        <option value="project" {{ old('type', $control->type) === 'project' ? 'selected' : '' }}>Project</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Control Factor -->
                <div>
                    <label for="factor" class="block text-sm font-medium text-gray-700 mb-1">Factor</label>
                    <div class="relative">
                        <input type="number" 
                               name="factor" 
                               id="factor" 
                               value="{{ old('factor', $control->factor) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('factor') border-red-300 @enderror"
                               placeholder="Enter control factor">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500">×</span>
                        </div>
                    </div>
                    @error('factor')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Grade -->
                <div>
                    <label for="max_grade" class="block text-sm font-medium text-gray-700 mb-1">Maximum Grade</label>
                    <div class="relative">
                        <input type="number" 
                               name="max_grade" 
                               id="max_grade" 
                               value="{{ old('max_grade', $control->max_grade) }}"
                               step="1"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('max_grade') border-red-300 @enderror"
                               placeholder="Enter maximum grade">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500">points</span>
                        </div>
                    </div>
                    @error('max_grade')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-6">
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Control
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Grades Section -->
    @if($control->grades->isNotEmpty())
    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900">Grade Distribution</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                    @php
                        $passCount = $control->grades->filter(function($grade) use ($control) {
                            return $grade->grade_value >= ($control->max_grade / 2);
                        })->count();
                        $passRate = ($passCount / $control->grades->count()) * 100;
                    @endphp
                    <div class="h-full bg-gradient-to-r from-green-500 to-green-400 transition-all duration-300" 
                         style="width: {{ $passRate }}%"></div>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="font-medium text-gray-700">
                        Pass Rate: <span class="text-green-600">{{ number_format($passRate, 1) }}%</span>
                    </span>
                    <span class="text-gray-500">
                        {{ $passCount }}/{{ $control->grades->count() }} Students
                        (Avg: {{ number_format($control->grades->avg('grade_value'), 2) }}/{{ $control->max_grade }})
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 