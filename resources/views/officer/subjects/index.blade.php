@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Subjects</h1>
    <a href="{{ route('officer.subjects.create') }}" class="bg-gray-900 text-white rounded px-4 py-2">New Subject</a>
</div>
<div class="bg-white rounded border overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
            <tr><th class="p-3 text-left">Code</th><th class="p-3 text-left">Title</th><th class="p-3">Actions</th></tr>
        </thead>
        <tbody>
            @forelse($subjects as $subject)
                <tr class="border-t">
                    <td class="p-3">{{ $subject->code }}</td>
                    <td class="p-3">{{ $subject->title }}</td>
                    <td class="p-3 text-right space-x-2">
                        <a class="text-blue-600" href="{{ route('officer.subjects.edit', $subject) }}">Edit</a>
                        <form class="inline" method="POST" action="{{ route('officer.subjects.destroy', $subject) }}">
                            @csrf @method('DELETE')
                            <button class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="p-3 text-gray-500">No subjects yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
