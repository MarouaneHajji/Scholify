@extends('layouts.admin-dashboard')

@section('content')
<div class="w-full">
    <!-- Header with back button -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Student Profile</h1>
            <a href="{{ route('students.index') }}" class="bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 border border-gray-300 transition duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-left text-gray-500"></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Profile Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 text-center">
                    <div class="relative inline-block">
                        @if($student->profile_picture)
                            <img class="h-32 w-32 rounded-full border-4 border-white shadow-lg object-cover mx-auto" 
                                src="{{ asset('storage/' . $student->profile_picture) }}" 
                                alt="{{ $student->first_name }}'s profile picture">
                        @else
                            <div class="h-32 w-32 rounded-full border-4 border-white shadow-lg bg-gray-100 flex items-center justify-center mx-auto">
                                <i class="fas fa-user-graduate text-4xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h2>
                    <p class="text-gray-500 text-sm">Student</p>
                    
                    <div class="mt-6">
                        <a href="{{ route('students.edit', $student->id) }}" 
                            class="inline-flex items-center justify-center w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    <div class="flex items-center py-2">
                        <div class="w-8 flex-shrink-0">
                            <i class="fas fa-envelope text-indigo-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-sm font-medium text-gray-900">{{ $student->user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center py-2">
                        <div class="w-8 flex-shrink-0">
                            <i class="fas fa-phone text-indigo-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="text-sm font-medium text-gray-900">{{ $student->phone_number ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-indigo-500"></i>
                        Personal Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500">First Name</span>
                            <span class="mt-1 text-gray-900">{{ $student->first_name }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500">Last Name</span>
                            <span class="mt-1 text-gray-900">{{ $student->last_name }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500">Date of Birth</span>
                            <span class="mt-1 text-gray-900">
                                {{ $student->date_of_birth ? date('F j, Y', strtotime($student->date_of_birth)) : 'Not provided' }}
                            </span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500">Class</span>
                            <span class="mt-1 text-gray-900">{{ $student->classes->pluck('name')->join(', ') ?: 'Not assigned' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-address-card mr-2 text-indigo-500"></i>
                        Contact Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500">Address</span>
                            <span class="mt-1 text-gray-900">{{ $student->address ?? 'Not provided' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            @if($student->extra_info)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-indigo-500"></i>
                        Additional Information
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-900 whitespace-pre-line">{{ $student->extra_info }}</p>
                </div>
            </div>
            @endif

            <!-- Class Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-graduation-cap mr-2 text-indigo-500"></i>
                        Class Information
                    </h3>
                </div>
                <div class="p-6">
                    @if($student->classes->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($student->classes as $class)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900">{{ $class->name }}</h4>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">Not enrolled in any class</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection