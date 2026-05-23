@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded border p-6">
        <h1 class="text-xl font-semibold mb-4">Login</h1>
        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm mb-1">Username</label>
                <input name="username" value="{{ old('username') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <label class="text-sm flex items-center gap-2"><input type="checkbox" name="remember"> Remember me</label>
            <button class="w-full bg-gray-900 text-white rounded px-4 py-2">Sign in</button>
        </form>
        <a href="{{ route('claim.create') }}" class="block mt-4 text-sm text-blue-600">Claim student account</a>
    </div>
@endsection
