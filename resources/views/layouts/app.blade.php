<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen">
<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="font-semibold">{{ config('app.name') }}</a>
        @auth
            <div class="flex items-center gap-3 text-sm">
                <span>{{ auth()->user()->username }} ({{ auth()->user()->role }})</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-3 py-1 bg-gray-900 text-white rounded">Logout</button>
                </form>
            </div>
        @endauth
    </div>
</nav>
<div class="max-w-7xl mx-auto px-4 py-6">
    @if (session('status'))
        <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-2">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-2">
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @auth
        @if (auth()->user()->role === 'officer')
            <div class="mb-6 flex flex-wrap gap-2 text-sm">
                <a class="px-3 py-1 rounded bg-white border" href="{{ route('officer.students.import.create') }}">Import Students</a>
                <a class="px-3 py-1 rounded bg-white border" href="{{ route('officer.subjects.index') }}">Subjects</a>
                <a class="px-3 py-1 rounded bg-white border" href="{{ route('officer.classes.index') }}">Classes</a>
            </div>
        @else
            <div class="mb-6 flex flex-wrap gap-2 text-sm">
                <a class="px-3 py-1 rounded bg-white border" href="{{ route('student.qr.show') }}">My QR</a>
                <a class="px-3 py-1 rounded bg-white border" href="{{ route('student.history.index') }}">Attendance History</a>
            </div>
        @endif
    @endauth
    {{ $slot ?? '' }}
    @yield('content')
</div>
</body>
</html>
