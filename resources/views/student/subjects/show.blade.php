@extends('layouts.student-dashboard')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('student.subjects.index') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Subjects
        </a>
    </div>

    <!-- Module Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $module->name }}</h1>
                    <p class="text-gray-600 mt-2">{{ $module->description }}</p>
                </div>
                <span class="bg-indigo-50 text-indigo-600 text-sm font-medium px-3 py-1 rounded-full">
                    {{ $module->coefficient }} Credits
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Teacher Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-tie text-indigo-500"></i>
                        Teacher Information
                    </h2>
                    @php
                        $teacher = $class->teachers->first(function($teacher) use ($module) {
                            return $teacher->modules->contains($module);
                        });
                    @endphp
                    @if($teacher)
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($teacher->profile_picture)
                                    <img class="h-12 w-12 rounded-full object-cover" 
                                         src="{{ asset('storage/' . $teacher->profile_picture) }}" 
                                         alt="{{ $teacher->first_name }} {{ $teacher->last_name }}">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <i class="fas fa-user text-indigo-500"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ $teacher->email }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-600">No teacher assigned to this module yet.</p>
                    @endif
                </div>
            </div>

            <!-- Assessments -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-tasks text-indigo-500"></i>
                        Assessments
                    </h2>
                    @if($module->controls && $module->controls->count() > 0)
                        <div class="space-y-4">
                            @foreach($module->controls as $control)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ $control->name }}</h3>
                                            @if($control->description)
                                                <p class="text-sm text-gray-600 mt-1">{{ $control->description }}</p>
                                            @endif
                                        </div>
                                        <span class="bg-indigo-50 text-indigo-600 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            {{ $control->percentage }}%
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">No assessments have been added for this module yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Class Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-indigo-500"></i>
                        Class Information
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Class</p>
                            <p class="text-sm font-medium text-gray-900">{{ $class->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Academic Year</p>
                            <p class="text-sm font-medium text-gray-900">{{ $class->academic_year }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resources -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-book text-indigo-500"></i>
                        Resources
                    </h2>
                    <div class="space-y-3">
                        @if($module->resources && $module->resources->count() > 0)
                            @foreach($module->resources as $resource)
                                <a href="{{ asset('storage/' . $resource->file_path) }}" 
                                   class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-file-pdf text-indigo-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $resource->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $resource->created_at->format('M d, Y') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <p class="text-gray-600 text-sm">No resources available yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 