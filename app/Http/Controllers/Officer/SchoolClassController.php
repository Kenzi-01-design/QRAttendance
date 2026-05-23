<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Officer\SchoolClassRequest;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    public function index(): View
    {
        return view('officer.classes.index', [
            'classes' => SchoolClass::with('subject')->orderByDesc('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('officer.classes.form', [
            'classModel' => new SchoolClass(),
            'subjects' => Subject::orderBy('code')->get(),
        ]);
    }

    public function store(SchoolClassRequest $request): RedirectResponse
    {
        SchoolClass::create($request->validated());

        return redirect()->route('officer.classes.index')->with('status', 'Class created.');
    }

    public function edit(SchoolClass $classroom): View
    {
        return view('officer.classes.form', [
            'classModel' => $classroom,
            'subjects' => Subject::orderBy('code')->get(),
        ]);
    }

    public function update(SchoolClassRequest $request, SchoolClass $classroom): RedirectResponse
    {
        $classroom->update($request->validated());

        return redirect()->route('officer.classes.index')->with('status', 'Class updated.');
    }

    public function destroy(SchoolClass $classroom): RedirectResponse
    {
        $classroom->delete();

        return redirect()->route('officer.classes.index')->with('status', 'Class deleted.');
    }
}
