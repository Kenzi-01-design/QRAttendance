<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\QrSignatureService;
use Illuminate\View\View;

class QrController extends Controller
{
    public function show(QrSignatureService $qr): View
    {
        $student = auth()->user()->student;

        abort_if(! $student || ! $student->student_no, 404);

        return view('student.qr', [
            'student' => $student,
            'payload' => $qr->payload($student->student_no),
        ]);
    }
}
