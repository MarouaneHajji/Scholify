@extends('layouts.student-dashboard')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 mb-6">
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-indigo-500"></i>
                        {{ $class_name }} Schedule
                    </h1>
                    <p class="text-gray-600 mt-2">Weekly class schedule details</p>
                </div>
                @if($has_schedule)
                    <div class="flex gap-2">
                        <a href="{{ route('student.schedule.download', ['class' => $schedule_raw->class_id]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                            <i class="fas fa-download mr-2"></i>
                            Download PDF
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Schedule Table -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <div class="p-4 sm:p-6">
            <!-- Mobile Schedule View (visible on small screens) -->
            <div class="block lg:hidden">
                @if($has_schedule && $schedule_decoded)
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                        <div class="mb-6 last:mb-0">
                            <h3 class="text-lg font-medium text-gray-900 bg-gray-50 p-3 rounded-lg mb-3">{{ $day }}</h3>
                            @if(isset($schedule_decoded[$day]) && count($schedule_decoded[$day]) > 0)
                                <div class="space-y-3">
                                    @foreach($schedule_decoded[$day] as $slot)
                                        <div class="bg-white border border-gray-200 rounded-lg p-3">
                                            <div class="text-sm">
                                                <div class="font-medium text-indigo-600 mb-1">
                                                    {{ $slot['start_time'] }} - {{ $slot['end_time'] }}
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
                @else
                    <p class="text-gray-500 text-center py-3 bg-gray-50 rounded-lg">No schedule available for this class</p>
                @endif
            </div>

            <!-- Desktop Schedule Table -->
            <div class="hidden lg:block">
                @if($has_schedule && $schedule_decoded)
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border border-gray-200 py-3.5 px-4 text-left text-sm font-semibold text-gray-900" style="width: 100px;">
                                        Day
                                    </th>
                                    @foreach(['8h à 9h', '9h à 10h', '10h à 11h', '11h à 12h', '12h à 13h', '13h à 14h', '14h à 15h', '15h à 16h', '16h à 17h', '17h à 18h'] as $timeSlot)
                                        <th class="border border-gray-200 px-4 py-3.5 text-center text-sm font-semibold text-gray-900" style="width: 120px;">
                                            {{ $timeSlot }}
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
                                        @foreach(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'] as $time)
                                            @if($skipCells > 0)
                                                @php $skipCells--; @endphp
                                                @continue
                                            @endif
                                            @php
                                                $currentSlot = null;
                                                foreach ($schedule_decoded[$day] ?? [] as $slot) {
                                                    if ($slot['start_time'] === $time) {
                                                        $currentSlot = $slot;
                                                        break;
                                                    }
                                                }
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
                @else
                    <p class="text-gray-500 text-center py-6">No schedule available for this class</p>
                @endif
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