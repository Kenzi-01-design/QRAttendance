<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Officer\SubjectRequest;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        return view('officer.subjects.index', [
            'subjects' => Subject::orderBy('code')->get(),
        ]);
    }

    public function create(): View
    {
        return view('officer.subjects.form', ['subject' => new Subject()]);
    }

    public function store(SubjectRequest $request): RedirectResponse
    {
        Subject::create($request->validated());

        return redirect()->route('officer.subjects.index')->with('status', 'Subject created.');
    }

    public function edit(Subject $subject): View
    {
        return view('officer.subjects.form', compact('subject'));
    }

    public function update(SubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validated());

        return redirect()->route('officer.subjects.index')->with('status', 'Subject updated.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('officer.subjects.index')->with('status', 'Subject deleted.');
    }
}
