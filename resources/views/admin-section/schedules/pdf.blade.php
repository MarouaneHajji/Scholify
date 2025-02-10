<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $schedule->class->name }} Schedule</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }
        .logo-header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .logo-container {
            width: 100%;
            text-align: center;
        }
        .logo-text {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }
        .schedule-info {
            text-align: center;
            margin: 20px 0;
        }
        .schedule-info h1 {
            font-size: 18px;
            color: #333;
            margin: 0;
        }
        .schedule-info p {
            font-size: 12px;
            color: #666;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 9px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .day-column {
            font-weight: bold;
            background-color: #f8f9fa;
            width: 80px;
        }
        .slot {
            background-color: #f3f4f6;
        }
        .slot-content {
            line-height: 1.4;
        }
        .slot-content strong {
            color: #2563eb;
        }
        .slot-content .subject {
            color: #1f2937;
        }
        .slot-content .room {
            color: #6b7280;
        }
        .slot-content .time {
            font-size: 8px;
            color: #9ca3af;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 10px;
            font-size: 8px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="logo-header">
        <div class="logo-container">
            <div style="text-align: center;">
                <img src="{{ public_path('images/ofppt-logo.png') }}" height="60" alt="OFPPT Logo">
            </div>
            <div class="logo-text">
                OFFICE DE LA FORMATION PROFESSIONNELLE<br>
                ET DE LA PROMOTION DU TRAVAIL - Provinces Sud
            </div>
        </div>
    </div>

    <div class="schedule-info">
        <h1>ISTA NTIC GUELMIM</h1>
        <p>Année de Formation : 2023/2024</p>
        <h2>{{ $schedule->class->name }} - Emploi du Temps</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Day</th>
                @foreach($timeSlots as $display)
                    <th>{{ $display }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                <tr>
                    <td class="day-column">{{ $day }}</td>
                    @php $skipCells = 0; @endphp
                    @foreach($timeSlots as $time => $display)
                        @if($skipCells > 0)
                            @php $skipCells--; @endphp
                            @continue
                        @endif
                        @php
                            $currentSlot = isset($slotsByDay[$day][$time]) ? $slotsByDay[$day][$time] : null;
                            if ($currentSlot) {
                                $startHour = intval(substr($currentSlot['start_time'], 0, 2));
                                $endHour = intval(substr($currentSlot['end_time'], 0, 2));
                                $colspan = $endHour - $startHour;
                                $skipCells = $colspan - 1;
                            }
                        @endphp
                        <td class="{{ $currentSlot ? 'slot' : '' }}" 
                            @if($currentSlot && $colspan > 1) colspan="{{ $colspan }}" @endif>
                            @if($currentSlot)
                                <div class="slot-content">
                                    <strong>{{ $currentSlot['teacher_name'] }}</strong><br>
                                    <span class="subject">{{ $currentSlot['module_name'] }}</span><br>
                                    <span class="room">Room: {{ $currentSlot['room'] }}</span><br>
                                    <span class="time">{{ $currentSlot['start_time'] }} - {{ $currentSlot['end_time'] }}</span>
                                </div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Directeur
    </div>
</body>
</html> 