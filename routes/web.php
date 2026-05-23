<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Officer\AttendanceSessionController;
use App\Http\Controllers\Officer\RosterController;
use App\Http\Controllers\Officer\SchoolClassController;
use App\Http\Controllers\Officer\StudentImportController;
use App\Http\Controllers\Officer\SubjectController;
use App\Http\Controllers\Student\AttendanceHistoryController;
use App\Http\Controllers\Student\ClaimAccountController;
use App\Http\Controllers\Student\QrController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('/claim', [ClaimAccountController::class, 'create'])->name('claim.create');
    Route::post('/claim', [ClaimAccountController::class, 'store'])->name('claim.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('officer')->name('officer.')->middleware('role:officer')->group(function (): void {
        Route::get('/students/import', [StudentImportController::class, 'create'])->name('students.import.create');
        Route::post('/students/import', [StudentImportController::class, 'store'])->name('students.import.store');

        Route::resource('subjects', SubjectController::class)->except(['show']);
        Route::resource('classes', SchoolClassController::class)->except(['show'])->parameters(['classes' => 'classroom']);

        Route::get('/classes/{classroom}/roster', [RosterController::class, 'index'])->name('classes.roster.index');
        Route::post('/classes/{classroom}/roster', [RosterController::class, 'add'])->name('classes.roster.add');
        Route::delete('/classes/{classroom}/roster/{student}', [RosterController::class, 'remove'])->name('classes.roster.remove');

        Route::get('/classes/{classroom}/sessions', [AttendanceSessionController::class, 'index'])->name('sessions.index');
        Route::get('/classes/{classroom}/sessions/create', [AttendanceSessionController::class, 'create'])->name('sessions.create');
        Route::post('/classes/{classroom}/sessions', [AttendanceSessionController::class, 'store'])->name('sessions.store');
        Route::get('/classes/{classroom}/sessions/{session}/edit', [AttendanceSessionController::class, 'edit'])->name('sessions.edit');
        Route::put('/classes/{classroom}/sessions/{session}', [AttendanceSessionController::class, 'update'])->name('sessions.update');
        Route::delete('/classes/{classroom}/sessions/{session}', [AttendanceSessionController::class, 'destroy'])->name('sessions.destroy');

        Route::post('/sessions/{session}/open', [AttendanceSessionController::class, 'open'])->name('sessions.open');
        Route::post('/sessions/{session}/close', [AttendanceSessionController::class, 'close'])->name('sessions.close');
        Route::get('/sessions/{session}/attendance', [AttendanceSessionController::class, 'attendanceList'])->name('sessions.attendance');
        Route::get('/sessions/{session}/scan', [AttendanceSessionController::class, 'scanPage'])->name('sessions.scan-page');
        Route::post('/sessions/{session}/scan', [AttendanceSessionController::class, 'scan'])->name('sessions.scan');
        Route::get('/sessions/{session}/export', [AttendanceSessionController::class, 'exportCsv'])->name('sessions.export');
    });

    Route::prefix('student')->name('student.')->middleware('role:student')->group(function (): void {
        Route::get('/qr', [QrController::class, 'show'])->name('qr.show');
        Route::get('/history', [AttendanceHistoryController::class, 'index'])->name('history.index');
    });
});
