@extends('layouts.app')

@section('content')
<div class="max-w-xl bg-white rounded border p-6">
    <h1 class="text-xl font-semibold mb-4">{{ $classModel->exists ? 'Edit Class' : 'New Class' }}</h1>
    <form method="POST" action="{{ $classModel->exists ? route('officer.classes.update', $classModel) : route('officer.classes.store') }}" class="space-y-4">
        @csrf
        @if($classModel->exists) @method('PUT') @endif
        <div>
            <label class="block text-sm mb-1">Subject</label>
            <select name="subject_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select subject</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected(old('subject_id', $classModel->subject_id) == $subject->id)>{{ $subject->code }} - {{ $subject->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm mb-1">Section</label>
            <input name="section" value="{{ old('section', $classModel->section) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm mb-1">School Year</label>
            <input name="school_year" value="{{ old('school_year', $classModel->school_year) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm mb-1">Semester</label>
            <input name="semester" value="{{ old('semester', $classModel->semester) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <button class="bg-gray-900 text-white rounded px-4 py-2">Save</button>
    </form>
</div>
@endsection
