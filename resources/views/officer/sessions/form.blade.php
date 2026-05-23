@extends('layouts.app')

@section('content')
<div class="max-w-xl bg-white rounded border p-6">
    <h1 class="text-xl font-semibold mb-4">{{ $session->exists ? 'Edit Session' : 'New Session' }}</h1>
    <form method="POST" action="{{ $session->exists ? route('officer.sessions.update', [$classroom, $session]) : route('officer.sessions.store', $classroom) }}" class="space-y-4">
        @csrf
        @if($session->exists) @method('PUT') @endif
        <div>
            <label class="block text-sm mb-1">Date</label>
            <input type="date" name="session_date" value="{{ old('session_date', optional($session->session_date)->toDateString()) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm mb-1">Start Time</label>
            <input type="time" name="start_time" value="{{ old('start_time', optional($session->start_time)->format('H:i')) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm mb-1">End Time</label>
            <input type="time" name="end_time" value="{{ old('end_time', optional($session->end_time)->format('H:i')) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm mb-1">Late Minutes</label>
            <input type="number" name="late_minutes" min="0" value="{{ old('late_minutes', $session->late_minutes) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                @foreach(['draft', 'open', 'closed'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $session->status) === $status)>{{ strtoupper($status) }}</option>
                @endforeach
            </select>
        </div>
        <button class="bg-gray-900 text-white rounded px-4 py-2">Save</button>
    </form>
</div>
@endsection
