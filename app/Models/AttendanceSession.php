<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'session_date',
        'start_time',
        'end_time',
        'late_minutes',
        'status',
        'opened_at',
        'opened_by',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'opened_at' => 'datetime',
            'late_minutes' => 'integer',
        ];
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'attendance_session_id');
    }

    public function startDateTime(): Carbon
    {
        return Carbon::parse($this->session_date->toDateString().' '.$this->start_time->format('H:i:s'));
    }

    public function endDateTime(): Carbon
    {
        return Carbon::parse($this->session_date->toDateString().' '.$this->end_time->format('H:i:s'));
    }
}
