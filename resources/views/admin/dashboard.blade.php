<div class="calendar-header">
    <div class="calendar-title text-white">{{ $currentMonth }} {{ $currentYear }}</div>
    <div class="calendar-nav">
        <a href="{{ route('admin.dashboard', ['timestamp' => $lastMonthTimestamp]) }}" class="calendar-nav-btn">
            <i class="bi bi-chevron-left"></i> Prev
        </a>
        <a href="{{ route('admin.dashboard', ['timestamp' => $nextMonthTimestamp]) }}" class="calendar-nav-btn">
            Next <i class="bi bi-chevron-right"></i>
        </a>
    </div>
</div>
