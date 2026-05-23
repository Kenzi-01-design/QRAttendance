@extends('layouts.app')

@section('content')
<h1 class="text-xl font-semibold mb-4">Roster: {{ $classroom->subject->code }} - {{ $classroom->section }}</h1>
<div class="grid md:grid-cols-2 gap-4">
    <div class="bg-white rounded border p-4">
        <h2 class="font-semibold mb-3">Add Student</h2>
        <form method="POST" action="{{ route('officer.classes.roster.add', $classroom) }}" class="space-y-3">
            @csrf
            <select name="student_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select student</option>
                @foreach($availableStudents as $student)
                    <option value="{{ $student->id }}">{{ $student->full_name }} ({{ $student->section }})</option>
                @endforeach
            </select>
            <button class="bg-gray-900 text-white rounded px-4 py-2">Add to Roster</button>
        </form>
    </div>
    <div class="bg-white rounded border overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="p-3 text-left">Student</th><th class="p-3 text-left">Section</th><th class="p-3"></th></tr></thead>
            <tbody>
                @forelse($classroom->students as $student)
                <tr class="border-t">
                    <td class="p-3">{{ $student->full_name }}</td>
                    <td class="p-3">{{ $student->section }}</td>
                    <td class="p-3 text-right">
                        <form method="POST" action="{{ route('officer.classes.roster.remove', [$classroom, $student]) }}">
                            @csrf @method('DELETE')
                            <button class="text-red-600">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="3" class="p-3 text-gray-500">No students in roster yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
