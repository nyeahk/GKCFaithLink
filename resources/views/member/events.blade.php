@extends('layouts.member')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<div class="p-4">
    <div class="text-center text-xl font-bold mb-4">
        {{ $carbon->format('F Y') }}
    </div>

    <div class="grid grid-cols-7 gap-1 text-center bg-blue-600 text-white font-semibold">
        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
            <div class="p-2">{{ $day }}</div>
        @endforeach
    </div>

    <div class="grid grid-cols-7 gap-1 mt-2">
        @foreach ($weeks as $week)
            @foreach ($week as $day)
                <div class="p-2 border text-center hover:bg-blue-100 cursor-pointer 
                    {{ $day->isSameDay(now()) ? 'bg-yellow-200 font-bold' : '' }}">
                    {{ $day->day }}
                    {{-- Optional: Add event indicator --}}
                    {{-- <span class="block text-xs text-gray-600">Event</span> --}}
                </div>
            @endforeach
        @endforeach
    </div>
</div>
@endsection
