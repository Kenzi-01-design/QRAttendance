<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'title'];

    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'subject_id');
    }
}
