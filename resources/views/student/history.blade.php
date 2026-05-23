@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold mb-4">Attendance History</h1>
<div class="bg-white rounded border overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
            <tr><th class="p-3 text-left">Subject</th><th class="p-3 text-left">Class</th><th class="p-3 text-left">Session Date</th><th class="p-3 text-left">Status</th><th class="p-3 text-left">Time In</th></tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
            <tr class="border-t">
                <td class="p-3">{{ $attendance->attendanceSession->schoolClass->subject->code }}</td>
                <td class="p-3">{{ $attendance->attendanceSession->schoolClass->section }}</td>
                <td class="p-3">{{ $attendance->attendanceSession->session_date->toDateString() }}</td>
                <td class="p-3">{{ $attendance->status }}</td>
                <td class="p-3">{{ optional($attendance->time_in)->toDateTimeString() ?? '-' }}</td>
            </tr>
            @empty
                <tr><td colspan="5" class="p-3 text-gray-500">No attendance history yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
