@extends('layouts.student-dashboard')

@section('content')
<div class="w-full">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-book text-indigo-500"></i>
                My Subjects
            </h1>
            <p class="text-gray-600 mt-2">View all your enrolled subjects and modules</p>
        </div>
    </div>

    @if($student->classes->isNotEmpty())
        @foreach($student->classes as $class)
            <!-- Class Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-indigo-500"></i>
                        {{ $class->name }}
                    </h2>

                    <!-- Modules Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($class->modules as $module)
                            <div class="bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200">
                                <div class="p-5">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $module->name }}</h3>
                                            @php
                                                $teacher = $class->teachers->first(function($teacher) use ($module) {
                                                    return $teacher->modules->contains($module);
                                                });
                                            @endphp
                                            <p class="text-sm text-gray-600 mt-1">
                                                <i class="fas fa-user-tie text-indigo-400 mr-1"></i>
                                                {{ $teacher ? $teacher->first_name . ' ' . $teacher->last_name : 'Not assigned' }}
                                            </p>
                                        </div>
                                        <span class="bg-indigo-50 text-indigo-600 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            {{ $module->coefficient }} Credits
                                        </span>
                                    </div>

                                    @if($module->description)
                                        <p class="text-gray-600 text-sm mt-3">
                                            {{ Str::limit($module->description, 100) }}
                                        </p>
                                    @endif

                                    <!-- Module Details -->
                                    <div class="mt-4 space-y-2">
                                        @if($module->controls->isNotEmpty())
                                            <div class="text-sm text-gray-600">
                                                <i class="fas fa-tasks text-indigo-400 mr-1"></i>
                                                {{ $module->controls->count() }} Assessments
                                            </div>
                                        @endif
                                    </div>

                                    <!-- View Details Button -->
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <a href="{{ route('student.subjects.show', $module->id) }}" 
                                           class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                            View Details
                                            <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <!-- No Classes Message -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="text-center">
                <i class="fas fa-book-open text-4xl text-gray-400 mb-3"></i>
                <h3 class="text-lg font-medium text-gray-900">No Subjects Available</h3>
                <p class="text-gray-600 mt-1">You are not enrolled in any classes yet.</p>
            </div>
        </div>
    @endif
</div>
@endsection 