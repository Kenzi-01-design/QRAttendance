<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Officer\AttendanceSessionRequest;
use App\Http\Requests\Officer\ScanAttendanceRequest;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\QrSignatureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class AttendanceSessionController extends Controller
{
    public function index(SchoolClass $classroom): View
    {
        return view('officer.sessions.index', [
            'classroom' => $classroom,
            'sessions' => $classroom->attendanceSessions()->orderByDesc('session_date')->orderByDesc('start_time')->get(),
        ]);
    }

    public function create(SchoolClass $classroom): View
    {
        return view('officer.sessions.form', [
            'classroom' => $classroom,
            'session' => new AttendanceSession(['status' => 'draft', 'late_minutes' => 0]),
        ]);
    }

    public function store(AttendanceSessionRequest $request, SchoolClass $classroom): RedirectResponse
    {
        $classroom->attendanceSessions()->create($request->validated());

        return redirect()->route('officer.sessions.index', $classroom)->with('status', 'Session created.');
    }

    public function edit(SchoolClass $classroom, AttendanceSession $session): View
    {
        abort_unless($session->class_id === $classroom->id, 404);

        return view('officer.sessions.form', compact('classroom', 'session'));
    }

    public function update(AttendanceSessionRequest $request, SchoolClass $classroom, AttendanceSession $session): RedirectResponse
    {
        abort_unless($session->class_id === $classroom->id, 404);

        $session->update($request->validated());

        return redirect()->route('officer.sessions.index', $classroom)->with('status', 'Session updated.');
    }

    public function destroy(SchoolClass $classroom, AttendanceSession $session): RedirectResponse
    {
        abort_unless($session->class_id === $classroom->id, 404);

        $session->delete();

        return back()->with('status', 'Session deleted.');
    }

    public function open(Request $request, AttendanceSession $session): RedirectResponse
    {
        $session->load('schoolClass.students');

        DB::transaction(function () use ($request, $session): void {
            foreach ($session->schoolClass->students as $student) {
                Attendance::firstOrCreate([
                    'attendance_session_id' => $session->id,
                    'student_id' => $student->id,
                ], [
                    'status' => 'ABSENT',
                ]);
            }

            $session->update([
                'status' => 'open',
                'opened_at' => now(),
                'opened_by' => $request->user()->id,
            ]);
        });

        return back()->with('status', 'Session opened and absences initialized.');
    }

    public function close(AttendanceSession $session): RedirectResponse
    {
        $session->update(['status' => 'closed']);

        return back()->with('status', 'Session closed.');
    }

    public function attendanceList(AttendanceSession $session): View
    {
        return view('officer.sessions.attendance', [
            'session' => $session->load(['schoolClass.subject', 'attendances.student']),
        ]);
    }

    public function exportCsv(AttendanceSession $session)
    {
        $session->load(['schoolClass.subject', 'attendances.student']);

        $filename = 'attendance-session-'.$session->id.'.csv';

        $callback = static function () use ($session): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['student_no', 'full_name', 'section', 'status', 'time_in']);
            foreach ($session->attendances as $attendance) {
                fputcsv($handle, [
                    $attendance->student->student_no,
                    $attendance->student->full_name,
                    $attendance->student->section,
                    $attendance->status,
                    optional($attendance->time_in)?->toDateTimeString(),
                ]);
            }
            fclose($handle);
        };

        return Response::streamDownload($callback, $filename, ['Content-Type' => 'text/csv']);
    }

    public function scanPage(AttendanceSession $session): View
    {
        return view('officer.sessions.scan', ['session' => $session->load('schoolClass.subject')]);
    }

    public function scan(ScanAttendanceRequest $request, AttendanceSession $session, QrSignatureService $qr): JsonResponse
    {
        if ($session->status !== 'open') {
            return response()->json(['ok' => false, 'message' => 'Session is not open.'], 422);
        }

        $data = $request->validated();

        if (! $qr->verify($data['sn'], $data['sig'])) {
            return response()->json(['ok' => false, 'message' => 'Invalid QR signature.'], 422);
        }

        $student = Student::query()
            ->where('student_no', $data['sn'])
            ->whereNotNull('claimed_at')
            ->where('status', 'active')
            ->first();

        if (! $student) {
            return response()->json(['ok' => false, 'message' => 'Student not found.'], 404);
        }

        $isRostered = $session->schoolClass()->whereHas('students', fn ($query) => $query->where('students.id', $student->id))->exists();

        if (! $isRostered) {
            return response()->json(['ok' => false, 'message' => 'Student is not in the class roster.'], 422);
        }

        $attendance = Attendance::firstOrCreate([
            'attendance_session_id' => $session->id,
            'student_id' => $student->id,
        ], ['status' => 'ABSENT']);

        if (in_array($attendance->status, ['PRESENT', 'LATE'], true)) {
            return response()->json([
                'ok' => false,
                'message' => 'Already recorded.',
                'student' => $student->full_name,
                'status' => $attendance->status,
                'time_in' => optional($attendance->time_in)?->toDateTimeString(),
            ], 200);
        }

        $now = Carbon::now();
        $start = $session->startDateTime();
        $lateCutoff = $start->copy()->addMinutes($session->late_minutes);
        $end = $session->endDateTime();

        if ($now->gt($end)) {
            return response()->json(['ok' => false, 'message' => 'Session ended.'], 422);
        }

        $status = $now->lte($lateCutoff) ? 'PRESENT' : 'LATE';

        $attendance->update([
            'status' => $status,
            'time_in' => $now,
            'scanned_by' => $request->user()->id,
        ]);

        return response()->json([
            'ok' => true,
            'student' => $student->full_name,
            'section' => $student->section,
            'status' => $status,
            'time_in' => $attendance->fresh()->time_in?->toDateTimeString(),
        ]);
    }
}
