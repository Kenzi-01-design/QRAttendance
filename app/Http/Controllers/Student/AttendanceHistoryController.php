<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AttendanceHistoryController extends Controller
{
    public function index(): View
    {
        $student = auth()->user()->student;

        return view('student.history', [
            'student' => $student,
            'attendances' => $student
                ? $student->attendances()->with(['attendanceSession.schoolClass.subject'])->latest('time_in')->get()
                : collect(),
        ]);
    }
}
