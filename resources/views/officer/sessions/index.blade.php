@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Sessions: {{ $classroom->subject->code }} - {{ $classroom->section }}</h1>
    <a href="{{ route('officer.sessions.create', $classroom) }}" class="bg-gray-900 text-white rounded px-4 py-2">New Session</a>
</div>
<div class="bg-white rounded border overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Date</th>
                <th class="p-3 text-left">Time</th>
                <th class="p-3 text-left">Late Minutes</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $session)
            <tr class="border-t">
                <td class="p-3">{{ $session->session_date->toDateString() }}</td>
                <td class="p-3">{{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}</td>
                <td class="p-3">{{ $session->late_minutes }}</td>
                <td class="p-3 uppercase">{{ $session->status }}</td>
                <td class="p-3 text-right space-x-2">
                    <a class="text-blue-600" href="{{ route('officer.sessions.edit', [$classroom, $session]) }}">Edit</a>
                    <a class="text-indigo-700" href="{{ route('officer.sessions.scan-page', $session) }}">Scan</a>
                    <a class="text-emerald-700" href="{{ route('officer.sessions.attendance', $session) }}">Attendance</a>
                    <a class="text-purple-700" href="{{ route('officer.sessions.export', $session) }}">Export</a>
                    @if($session->status !== 'open')
                        <form class="inline" method="POST" action="{{ route('officer.sessions.open', $session) }}">@csrf<button class="text-green-700">Open</button></form>
                    @endif
                    @if($session->status === 'open')
                        <form class="inline" method="POST" action="{{ route('officer.sessions.close', $session) }}">@csrf<button class="text-orange-700">Close</button></form>
                    @endif
                    <form class="inline" method="POST" action="{{ route('officer.sessions.destroy', [$classroom, $session]) }}">@csrf @method('DELETE')<button class="text-red-600">Delete</button></form>
                </td>
            </tr>
            @empty
                <tr><td colspan="5" class="p-3 text-gray-500">No sessions yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
