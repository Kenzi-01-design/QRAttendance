<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Officer\RosterAddRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RosterController extends Controller
{
    public function index(SchoolClass $classroom): View
    {
        return view('officer.classes.roster', [
            'classroom' => $classroom->load(['students' => fn ($query) => $query->orderBy('full_name')]),
            'availableStudents' => Student::where('status', 'active')
                ->whereNotIn('id', $classroom->students()->pluck('students.id'))
                ->orderBy('full_name')
                ->get(),
        ]);
    }

    public function add(RosterAddRequest $request, SchoolClass $classroom): RedirectResponse
    {
        $classroom->students()->syncWithoutDetaching([$request->validated('student_id')]);

        return back()->with('status', 'Student added to roster.');
    }

    public function remove(SchoolClass $classroom, Student $student): RedirectResponse
    {
        $classroom->students()->detach($student->id);

        return back()->with('status', 'Student removed from roster.');
    }
}
