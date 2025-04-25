@extends('layouts.member')

@section('content')
<div class="dashboard-container">
    <div id="calendar"></div>
</div>

<!-- Include FullCalendar CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                @foreach ($events as $event)
                {
                    title: '{{ $event->title }}',
                    start: '{{ $event->start_date }}',
                    end: '{{ $event->end_date }}',
                },
                @endforeach
            ],
            dateClick: function (info) {
                alert('Clicked on: ' + info.dateStr);
            },
            eventClick: function (info) {
                alert('Event: ' + info.event.title);
            }
        });

        calendar.render();
    });
</script>
@endsection
