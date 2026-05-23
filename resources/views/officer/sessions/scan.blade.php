@extends('layouts.app')

@section('content')
<div class="grid lg:grid-cols-2 gap-4">
    <div class="bg-white rounded border p-4">
        <h1 class="text-xl font-semibold mb-2">Scan Attendance</h1>
        <p class="text-sm text-gray-600 mb-4">
            {{ $session->schoolClass->subject->code }} - {{ $session->schoolClass->section }}<br>
            Session {{ $session->session_date->toDateString() }} | Status: <span class="uppercase">{{ $session->status }}</span>
        </p>
        <div id="reader" class="w-full"></div>
    </div>
    <div class="bg-white rounded border p-4">
        <h2 class="font-semibold mb-2">Result</h2>
        <div id="scan-result" class="rounded px-4 py-3 bg-gray-100 text-sm">Waiting for scan...</div>
    </div>
</div>
<script src="https://unpkg.com/html5-qrcode" defer></script>
<script>
window.addEventListener('DOMContentLoaded', () => {
    const result = document.getElementById('scan-result');
    const scanner = new Html5Qrcode('reader');

    const handleScan = async (decodedText) => {
        try {
            const params = new URLSearchParams(decodedText);
            const sn = params.get('sn');
            const sig = params.get('sig');
            if (!sn || !sig) throw new Error('Invalid QR payload');

            const response = await fetch('{{ route('officer.sessions.scan', $session) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ sn, sig })
            });
            const data = await response.json();

            if (data.ok) {
                result.className = 'rounded px-4 py-3 bg-green-100 text-green-800 text-sm';
                result.textContent = `${data.student} (${data.section}) - ${data.status} at ${data.time_in}`;
            } else {
                result.className = 'rounded px-4 py-3 bg-yellow-100 text-yellow-800 text-sm';
                result.textContent = data.message || 'Scan rejected';
            }
        } catch (error) {
            result.className = 'rounded px-4 py-3 bg-red-100 text-red-800 text-sm';
            result.textContent = error.message;
        }
    };

    scanner.start({ facingMode: 'environment' }, { fps: 10, qrbox: 220 }, handleScan);
});
</script>
@endsection
