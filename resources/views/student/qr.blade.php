@extends('layouts.app')

@section('content')
<div class="max-w-xl bg-white rounded border p-6">
    <h1 class="text-xl font-semibold mb-2">My QR Code</h1>
    <p class="text-sm text-gray-600 mb-4">Student: {{ $student->full_name }} ({{ $student->student_no }})</p>
    <div id="qrcode" class="p-4 bg-white inline-block border"></div>
    <p class="mt-3 text-xs text-gray-500 break-all">Payload: {{ $payload }}</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script>
QRCode.toCanvas(document.createElement('canvas'), '{{ $payload }}', { width: 260 }, (err, canvas) => {
    if (!err) document.getElementById('qrcode').appendChild(canvas);
});
</script>
@endsection
