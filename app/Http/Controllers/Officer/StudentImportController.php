<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Officer\ImportStudentsRequest;
use App\Models\Student;
use App\Services\StudentNameNormalizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentImportController extends Controller
{
    public function create(): View
    {
        return view('officer.students.import');
    }

    public function store(ImportStudentsRequest $request): RedirectResponse
    {
        $rows = array_map('str_getcsv', file($request->file('csv_file')->getRealPath()) ?: []);

        if (count($rows) < 2) {
            return back()->withErrors(['csv_file' => 'CSV must include a header and at least one row.']);
        }

        $header = array_map(fn ($item) => strtolower(trim((string) $item)), array_shift($rows));
        $fullNameIndex = array_search('full_name', $header, true);
        $sectionIndex = array_search('section', $header, true);

        if ($fullNameIndex === false || $sectionIndex === false) {
            return back()->withErrors(['csv_file' => 'CSV must have full_name and section columns.']);
        }

        $created = 0;

        foreach ($rows as $row) {
            $fullName = trim((string) ($row[$fullNameIndex] ?? ''));
            $section = trim((string) ($row[$sectionIndex] ?? ''));

            if ($fullName === '' || $section === '') {
                continue;
            }

            Student::firstOrCreate(
                [
                    'full_name_normalized' => StudentNameNormalizer::normalize($fullName),
                    'section' => $section,
                ],
                [
                    'full_name' => $fullName,
                    'status' => 'active',
                ],
            );

            $created++;
        }

        return back()->with('status', "Processed {$created} rows.");
    }
}
