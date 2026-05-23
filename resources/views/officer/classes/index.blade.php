@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Classes</h1>
    <a href="{{ route('officer.classes.create') }}" class="bg-gray-900 text-white rounded px-4 py-2">New Class</a>
</div>
<div class="bg-white rounded border overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">Subject</th>
                <th class="p-3 text-left">Section</th>
                <th class="p-3 text-left">School Year</th>
                <th class="p-3 text-left">Semester</th>
                <th class="p-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($classes as $class)
            <tr class="border-t">
                <td class="p-3">{{ $class->subject->code }} - {{ $class->subject->title }}</td>
                <td class="p-3">{{ $class->section }}</td>
                <td class="p-3">{{ $class->school_year }}</td>
                <td class="p-3">{{ $class->semester }}</td>
                <td class="p-3 text-right space-x-2">
                    <a class="text-blue-600" href="{{ route('officer.classes.edit', $class) }}">Edit</a>
                    <a class="text-emerald-700" href="{{ route('officer.classes.roster.index', $class) }}">Roster</a>
                    <a class="text-indigo-700" href="{{ route('officer.sessions.index', $class) }}">Sessions</a>
                    <form class="inline" method="POST" action="{{ route('officer.classes.destroy', $class) }}">
                        @csrf @method('DELETE')
                        <button class="text-red-600">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
                <tr><td colspan="5" class="p-3 text-gray-500">No classes yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
