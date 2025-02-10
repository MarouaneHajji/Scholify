@extends('layouts.student-dashboard')

@section('content')
<div class="w-full">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-user-graduate text-indigo-500"></i>
                Welcome, {{ $student->first_name }} {{ $student->last_name }}!
            </h1>
            <p class="text-gray-600 mt-2">Welcome to your student portal</p>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                    <i class="fas fa-id-card text-indigo-500"></i>
                    Profile Information
                </h2>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-8 flex-shrink-0">
                            <i class="fas fa-envelope text-indigo-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-sm font-medium text-gray-900">{{ $student->user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 flex-shrink-0">
                            <i class="fas fa-phone text-indigo-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="text-sm font-medium text-gray-900">{{ $student->phone_number ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 flex-shrink-0">
                            <i class="fas fa-graduation-cap text-indigo-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Class</p>
                            <p class="text-sm font-medium text-gray-900">{{ $student->class ?? 'Not assigned' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                    <i class="fas fa-book text-indigo-500"></i>
                    Academic Information
                </h2>
                <div class="space-y-3">
                    @if($student->classes->isNotEmpty())
                        @foreach($student->classes as $class)
                            <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg">
                                <div>
                                    <p class="text-sm text-gray-500">Current Class</p>
                                    <p class="text-sm font-medium text-indigo-700">{{ $class->name }}</p>
                                    <a href="{{ route('student.schedule', $class->id) }}" 
                                       class="text-xs text-indigo-600 hover:text-indigo-800 mt-1 inline-block">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        View Schedule
                                    </a>
                                </div>
                                <i class="fas fa-graduation-cap text-indigo-500"></i>
                            </div>
                            
                            <!-- Show class modules -->
                            <div class="mt-4">
                                <h3 class="text-sm font-medium text-gray-700 mb-2">Modules:</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($class->modules as $module)
                                        <div class="bg-white border border-gray-200 rounded-lg p-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $module->name }}</p>
                                            <p class="text-xs text-gray-600">
                                                Teacher: 
                                                @php
                                                    $teacher = $class->teachers->first(function($teacher) use ($module) {
                                                        return $teacher->modules->contains($module);
                                                    });
                                                @endphp
                                                {{ $teacher ? $teacher->first_name . ' ' . $teacher->last_name : 'Not assigned' }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-500">Current Class</p>
                                <p class="text-sm font-medium text-gray-700">Not enrolled in any class</p>
                            </div>
                            <i class="fas fa-graduation-cap text-gray-400"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2 mb-4">
                    <i class="fas fa-link text-indigo-500"></i>
                    Quick Links
                </h2>
                <div class="space-y-3">
                    @if($student->classes->isNotEmpty())
                        <a href="{{ route('student.schedule', $student->classes->first()->id) }}" 
                           class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition duration-200">
                            <span class="text-sm font-medium text-indigo-700">View Schedule</span>
                            <i class="fas fa-calendar-alt text-indigo-500"></i>
                        </a>
                    @else
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-400">View Schedule (No Class Assigned)</span>
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                        </div>
                    @endif
                    <a href="#" class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition duration-200">
                        <span class="text-sm font-medium text-indigo-700">View Subjects</span>
                        <i class="fas fa-book-open text-indigo-500"></i>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition duration-200">
                        <span class="text-sm font-medium text-indigo-700">View Grades</span>
                        <i class="fas fa-chart-line text-indigo-500"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any custom JavaScript for the student dashboard here
</script>
@endpush
