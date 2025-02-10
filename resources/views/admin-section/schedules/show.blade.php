@extends('layouts.admin-dashboard')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 mb-6">
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-indigo-500"></i>
                        {{ $schedule->class->name }} Schedule
                    </h1>
                    <p class="text-gray-600 mt-2">Weekly class schedule details</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('schedules.edit', $schedule->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Schedule
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Table -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <div class="p-4 sm:p-6">
            <!-- Mobile Schedule View (visible on small screens) -->
            <div class="block lg:hidden">
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                    <div class="mb-6 last:mb-0">
                        <h3 class="text-lg font-medium text-gray-900 bg-gray-50 p-3 rounded-lg mb-3">{{ $day }}</h3>
                        @if(isset($slotsByDay[$day]) && count($slotsByDay[$day]) > 0)
                            <div class="space-y-3">
                                @foreach($slotsByDay[$day] as $time => $slot)
                                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                                        <div class="text-sm">
                                            <div class="font-medium text-indigo-600 mb-1">
                                                {{ substr($slot['start_time'], 0, 5) }} - {{ substr($slot['end_time'], 0, 5) }}
                                            </div>
                                            <div class="font-medium text-gray-900">{{ $slot['teacher_name'] }}</div>
                                            <div class="text-gray-600">{{ $slot['module_name'] }}</div>
                                            <div class="text-gray-500 text-xs mt-1">Room: {{ $slot['room'] }}</div>
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
            <div class="hidden lg:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border border-gray-200 py-3.5 px-4 text-left text-sm font-semibold text-gray-900" style="width: 100px;">
                                    Day
                                </th>
                                @foreach($timeSlots as $time => $display)
                                    <th class="border border-gray-200 px-4 py-3.5 text-center text-sm font-semibold text-gray-900" style="width: 120px;">
                                        {{ $display }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                <tr>
                                    <td class="border border-gray-200 py-4 px-4 text-sm font-medium text-gray-900 bg-gray-50">
                                        {{ $day }}
                                    </td>
                                    @php $skipCells = 0; @endphp
                                    @foreach($timeSlots as $time => $display)
                                        @if($skipCells > 0)
                                            @php $skipCells--; @endphp
                                            @continue
                                        @endif
                                        @php
                                            $currentSlot = isset($slotsByDay[$day][$time]) ? $slotsByDay[$day][$time] : null;
                                            $colspan = 1;
                                            if ($currentSlot) {
                                                $startHour = intval(substr($currentSlot['start_time'], 0, 2));
                                                $endHour = intval(substr($currentSlot['end_time'], 0, 2));
                                                $colspan = $endHour - $startHour;
                                                $skipCells = $colspan - 1;
                                            }
                                        @endphp
                                        <td class="border border-gray-200 p-3 text-sm {{ $currentSlot ? 'bg-indigo-50' : '' }}" 
                                            @if($currentSlot && $colspan > 1) colspan="{{ $colspan }}" @endif>
                                            @if($currentSlot)
                                                <div class="flex flex-col items-center gap-1 min-h-[4rem]">
                                                    <div class="font-medium text-gray-900">{{ $currentSlot['teacher_name'] }}</div>
                                                    <div class="text-gray-600">{{ $currentSlot['module_name'] }}</div>
                                                    <div class="text-gray-500 text-xs">Room: {{ $currentSlot['room'] }}</div>
                                                </div>
                                            @else
                                                <div class="h-16"></div>
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

<style>
    @media (max-width: 1024px) {
        .schedule-container {
            margin: -1rem;
        }
    }
</style>
@endsection