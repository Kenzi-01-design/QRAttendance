@extends('layouts.app')

@section('content')
<div class="max-w-xl bg-white rounded border p-6">
    <h1 class="text-xl font-semibold mb-4">{{ $subject->exists ? 'Edit Subject' : 'New Subject' }}</h1>
    <form method="POST" action="{{ $subject->exists ? route('officer.subjects.update', $subject) : route('officer.subjects.store') }}" class="space-y-4">
        @csrf
        @if($subject->exists) @method('PUT') @endif
        <div>
            <label class="block text-sm mb-1">Code</label>
            <input name="code" value="{{ old('code', $subject->code) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm mb-1">Title</label>
            <input name="title" value="{{ old('title', $subject->title) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <button class="bg-gray-900 text-white rounded px-4 py-2">Save</button>
    </form>
</div>
@endsection
