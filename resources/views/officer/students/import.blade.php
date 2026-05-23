@extends('layouts.app')

@section('content')
<div class="max-w-2xl bg-white rounded border p-6">
    <h1 class="text-xl font-semibold mb-4">Import Students CSV</h1>
    <p class="text-sm text-gray-600 mb-4">Required headers: <code>full_name</code>, <code>section</code>.</p>
    <form method="POST" enctype="multipart/form-data" action="{{ route('officer.students.import.store') }}" class="space-y-4">
        @csrf
        <input type="file" name="csv_file" accept=".csv,text/csv" class="w-full border rounded px-3 py-2" required>
        <button class="bg-gray-900 text-white rounded px-4 py-2">Upload CSV</button>
    </form>
</div>
@endsection
