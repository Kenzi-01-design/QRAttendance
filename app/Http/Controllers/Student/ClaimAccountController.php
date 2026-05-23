<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\ClaimRequest;
use App\Models\Student;
use App\Models\User;
use App\Services\StudentNameNormalizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClaimAccountController extends Controller
{
    public function create(): View
    {
        return view('auth.claim');
    }

    public function store(ClaimRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $normalizedName = StudentNameNormalizer::normalize($data['full_name']);

        /** @var Student|null $student */
        $student = Student::query()
            ->where('full_name_normalized', $normalizedName)
            ->where('section', $data['section'])
            ->whereNull('student_no')
            ->whereNull('user_id')
            ->first();

        if (! $student) {
            return back()->withErrors([
                'full_name' => 'No matching unclaimed student record found.',
            ])->withInput();
        }

        DB::transaction(function () use ($student, $data): void {
            $user = User::create([
                'username' => $data['student_no'],
                'password' => $data['password'],
                'role' => 'student',
            ]);

            $student->update([
                'user_id' => $user->id,
                'student_no' => $data['student_no'],
                'claimed_at' => now(),
            ]);
        });

        return redirect()->route('login')->with('status', 'Account claimed. Please log in.');
    }
}
