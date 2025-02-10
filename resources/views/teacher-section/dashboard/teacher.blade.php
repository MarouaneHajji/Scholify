@extends('layouts.teacher-dashboard')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600 mt-1">Welcome back, {{ auth()->user()->teacher->first_name }} {{ auth()->user()->teacher->last_name }}</p>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Classes Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="font-semibold text-xl text-gray-800">{{ $stats['classes'] }}</h2>
                        <p class="text-gray-600">My Classes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="font-semibold text-xl text-gray-800">{{ $stats['students'] }}</h2>
                        <p class="text-gray-600">Total Students</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subjects Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="font-semibold text-xl text-gray-800">{{ $stats['modules'] }}</h2>
                        <p class="text-gray-600">My Modules</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="font-semibold text-xl text-gray-800">{{ $stats['assignments'] }}</h2>
                        <p class="text-gray-600">Assignments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Table Section -->
    <div class="mt-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">My Schedule</h2>
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-md overflow-hidden">
            <div class="p-4 sm:p-6">
                <!-- Mobile Schedule View -->
                <div class="block lg:hidden">
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                        <div class="mb-6 last:mb-0">
                            <h3 class="text-lg font-medium text-gray-900 bg-gray-50 p-3 rounded-lg mb-3">{{ $day }}</h3>
                            @if(isset($scheduleSlots[$day]) && count($scheduleSlots[$day]) > 0)
                                <div class="space-y-3">
                                    @foreach($scheduleSlots[$day] as $slot)
                                        <div class="flex items-center gap-4 bg-white p-3 rounded-lg border border-gray-200">
                                            <div class="text-sm">
                                                <div class="font-medium text-indigo-600 mb-1">
                                                    {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}
                                                </div>
                                                @if($slot->schedule && $slot->schedule->class)
                                                    <div class="font-medium text-gray-900">{{ $slot->schedule->class->name }}</div>
                                                @endif
                                                @if($slot->module)
                                                    <div class="text-gray-600">{{ $slot->module->name }}</div>
                                                @endif
                                                <div class="text-gray-500 text-xs mt-1">Room: {{ $slot->room }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-3 bg-gray-50 rounded-lg">No classes scheduled</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Schedule Table -->
                <div class="hidden lg:block overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 px-4 text-left text-sm font-semibold text-gray-900 w-24">
                                        Day
                                    </th>
                                    @foreach($timeSlots as $time => $display)
                                        <th scope="col" class="px-4 py-3.5 text-center text-sm font-semibold text-gray-900">
                                            {{ $display }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 px-4 text-sm font-medium text-gray-900 bg-gray-50">
                                            {{ $day }}
                                        </td>
                                        @php $skipCells = 0; @endphp
                                        @foreach($timeSlots as $time => $display)
                                            @if($skipCells > 0)
                                                @php $skipCells--; @endphp
                                                @continue
                                            @endif
                                            @php
                                                $currentSlot = isset($scheduleSlots[$day]) ? 
                                                    $scheduleSlots[$day]->first(function($slot) use ($time) {
                                                        return substr($slot->start_time, 0, 5) === $time;
                                                    }) : null;
                                                
                                                if ($currentSlot) {
                                                    $startHour = intval(substr($currentSlot->start_time, 0, 2));
                                                    $endHour = intval(substr($currentSlot->end_time, 0, 2));
                                                    $colspan = $endHour - $startHour;
                                                    $skipCells = $colspan - 1;
                                                }
                                            @endphp
                                            <td class="whitespace-nowrap p-3 text-sm {{ $currentSlot ? 'bg-indigo-50' : '' }}" 
                                                @if($currentSlot && $colspan > 1) colspan="{{ $colspan }}" @endif>
                                                @if($currentSlot)
                                                    <div class="flex flex-col items-center gap-1">
                                                        <div class="font-medium text-gray-900">{{ $currentSlot->schedule->class->name }}</div>
                                                        <div class="text-gray-600">{{ $currentSlot->module->name }}</div>
                                                        <div class="text-gray-500 text-xs">Room: {{ $currentSlot->room }}</div>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="mt-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Recent Activity</h2>
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-md p-6">
            <!-- Add recent activity content here -->
            <p class="text-gray-600">No recent activity to display.</p>
        </div>
    </div>
</div>
@endsection