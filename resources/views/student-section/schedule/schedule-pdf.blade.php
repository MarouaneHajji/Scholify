<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Class Schedule - {{ $class_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #4F46E5;
            font-size: 24px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #E5E7EB;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #F9FAFB;
            font-size: 12px;
            text-transform: uppercase;
            color: #6B7280;
        }
        td {
            font-size: 14px;
            color: #374151;
            vertical-align: top;
            height: 60px;
        }
        .day-column {
            background-color: #F9FAFB;
            font-weight: bold;
            text-align: left;
        }
        .module {
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 4px;
        }
        .teacher {
            color: #6B7280;
            font-size: 12px;
            margin-bottom: 2px;
        }
        .room {
            color: #9CA3AF;
            font-size: 11px;
        }
        .slot-cell {
            background-color: #EEF2FF;
            padding: 8px;
        }
    </style>
</head>
<body>
    <h1>Class Schedule - {{ $class_name }}</h1>
    
    <table>
        <thead>
            <tr>
                <th style="width: 100px;">Day</th>
                @foreach(['8h à 9h', '9h à 10h', '10h à 11h', '11h à 12h', '12h à 13h', '13h à 14h', '14h à 15h', '15h à 16h', '16h à 17h', '17h à 18h'] as $timeSlot)
                    <th>{{ $timeSlot }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                <tr>
                    <td class="day-column">{{ $day }}</td>
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
                        <td @if($colspan > 1) colspan="{{ $colspan }}" @endif @if($currentSlot) class="slot-cell" @endif>
                            @if($currentSlot)
                                <div class="module">{{ $currentSlot['module_name'] }}</div>
                                <div class="teacher">{{ $currentSlot['teacher_name'] }}</div>
                                <div class="room">Room: {{ $currentSlot['room'] }}</div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 