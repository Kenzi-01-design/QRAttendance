@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto bg-white rounded border p-6">
        <h1 class="text-xl font-semibold mb-4">Claim Student Account</h1>
        <form method="POST" action="{{ route('claim.store') }}" class="grid grid-cols-1 gap-4">
            @csrf
            <div>
                <label class="block text-sm mb-1">Full Name</label>
                <input name="full_name" value="{{ old('full_name') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm mb-1">Section</label>
                <input name="section" value="{{ old('section') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm mb-1">Student Number (Username)</label>
                <input name="student_no" value="{{ old('student_no') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
            </div>
            <button class="bg-gray-900 text-white rounded px-4 py-2">Claim Account</button>
        </form>
    </div>
@endsection
