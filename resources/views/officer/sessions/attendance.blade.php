@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold mb-4">Attendance: {{ $session->schoolClass->subject->code }} {{ $session->schoolClass->section }} ({{ $session->session_date->toDateString() }})</h1>
<div class="bg-white rounded border overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
            <tr><th class="p-3 text-left">Student No</th><th class="p-3 text-left">Student</th><th class="p-3 text-left">Section</th><th class="p-3 text-left">Status</th><th class="p-3 text-left">Time In</th></tr>
        </thead>
        <tbody>
            @forelse($session->attendances as $attendance)
            <tr class="border-t">
                <td class="p-3">{{ $attendance->student->student_no }}</td>
                <td class="p-3">{{ $attendance->student->full_name }}</td>
                <td class="p-3">{{ $attendance->student->section }}</td>
                <td class="p-3 font-medium">{{ $attendance->status }}</td>
                <td class="p-3">{{ optional($attendance->time_in)->toDateTimeString() ?? '-' }}</td>
            </tr>
            @empty
                <tr><td colspan="5" class="p-3 text-gray-500">No attendance records yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
