@extends('layouts.teacher-dashboard')

@section('title', 'My Classes')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">My Classes</h1>
            <p class="text-gray-600">View and manage your assigned classes</p>
        </div>
    </div>

    @if($classes->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($classes as $class)
                <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                    <!-- Class Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">{{ $class->name }}</h3>
                    </div>
                    
                    <!-- Class Content -->
                    <div class="p-6">
                        <!-- Quick Stats -->
                        <div class="text-center p-4 bg-indigo-50 rounded-lg mb-6">
                            <p class="text-sm text-gray-600 mb-1">Students</p>
                            <p class="text-2xl font-semibold text-indigo-600">{{ $class->students_count }}</p>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('teacher.classes.show', $class->id) }}" 
                           class="block w-full px-4 py-2.5 bg-indigo-600 text-white text-center rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                            <i class="fas fa-eye mr-2"></i>View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-8 text-center shadow-md border border-gray-200/50">
            <div class="inline-block p-4 bg-indigo-100 rounded-full mb-4">
                <i class="fas fa-chalkboard text-4xl text-indigo-500"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">No Classes Assigned Yet</h3>
            <p class="text-gray-600">You haven't been assigned to any classes yet.</p>
        </div>
    @endif
</div>
@endsection