@extends('layouts.admin-dashboard')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 mb-6">
        <div class="p-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-indigo-500"></i>
                    Schedule Management
                </h1>
                <p class="text-gray-600 mt-2">Manage and organize class schedules.</p>
            </div>
            <a href="{{ route('schedules.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-plus mr-2"></i>
                Create Schedule
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Schedule Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($schedules as $schedule)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-gray-300 hover:shadow-md transition-all duration-300">
                <div class="p-6">
                    <!-- Class Name -->
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $schedule->class->name }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Class Schedule</p>
                        </div>
                        <div class="h-10 w-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-indigo-500"></i>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('schedules.show', $schedule->id) }}" 
                           class="group relative inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-300 rounded-lg transition-all duration-150 shadow-sm">
                            <i class="fas fa-eye text-indigo-500 group-hover:text-indigo-600"></i>
                            <span>View</span>
                        </a>

                        <a href="{{ route('schedules.edit', $schedule->id) }}" 
                           class="group relative inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-300 rounded-lg transition-all duration-150 shadow-sm">
                            <i class="fas fa-edit text-yellow-500 group-hover:text-yellow-600"></i>
                            <span>Edit</span>
                        </a>

                        <a href="{{ route('schedules.pdf', $schedule->id) }}" 
                           class="group relative inline-flex items-center justify-center w-9 h-9 bg-white border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-300 rounded-lg transition-all duration-150 shadow-sm"
                           target="_blank"
                           title="Download PDF">
                            <i class="fas fa-file-pdf text-green-500 group-hover:text-green-600"></i>
                        </a>

                        <form action="{{ route('schedules.destroy', $schedule->id) }}" 
                              method="POST" 
                              class="inline-block"
                              onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="group relative inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-200 text-sm font-medium text-gray-700 hover:bg-red-50 hover:border-red-200 hover:text-red-600 rounded-lg transition-all duration-150 shadow-sm">
                                <i class="fas fa-trash text-red-500 group-hover:text-red-600"></i>
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 text-center">
                    <div class="text-gray-500">
                        <i class="fas fa-calendar-times text-4xl mb-4"></i>
                        <p class="text-lg">No schedules found.</p>
                        <a href="{{ route('schedules.create') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 mt-4 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">
                            <i class="fas fa-plus"></i>
                            <span>Create First Schedule</span>
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Success Message Toast -->
@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" 
         x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif
@endsection