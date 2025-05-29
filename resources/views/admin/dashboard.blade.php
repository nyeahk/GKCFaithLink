@extends('layouts.gkc')

@section('title', 'GKC FaithLink Admin Dashboard')

@section('content')
<div class="container">

    <div class="main-content">
        <table id="cal-table">
            <tr>
                <td colspan="7" style="text-align: center;">
                    <a href="{{ route('admin.dashboard', ['timestamp' => $todayTimestamp ?? now()->timestamp]) }}">Today</a>
                </td>
            </tr>
            <tr>
                <td><a href="{{ route('admin.dashboard', ['timestamp' => $lastMonthTimestamp ?? now()->subMonth()->timestamp]) }}"> << Prev</a></td>
                <th colspan="5">{{ ($currentDate ?? now())->format('F Y') }}</th>
                <td><a href="{{ route('admin.dashboard', ['timestamp' => $nextMonthTimestamp ?? now()->addMonth()->timestamp]) }}">Next >></a></td>
            </tr>
            <tr>
                <td class="day-headings">Sun</td>
                <td class="day-headings">Mon</td>
                <td class="day-headings">Tue</td>
                <td class="day-headings">Wed</td>
                <td class="day-headings">Thu</td>
                <td class="day-headings">Fri</td>
                <td class="day-headings">Sat</td>
            </tr>

            @foreach ($calendar ?? [] as $day)
                @if ($loop->index % ($daysInAWeek ?? 7) == 0)
                    <tr>
                @endif

                <td class="{{ isset($currentDate) && $day == $currentDate->day ? 'highlight' : '' }}">
                    {{ $day ?? '' }}
                </td>

                @if (($loop->index + 1) % ($daysInAWeek ?? 7) == 0)
                    </tr>
                @endif
            @endforeach
        </table>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
