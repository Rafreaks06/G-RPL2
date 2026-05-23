<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_program_id',
        'code',
        'name',
        'semester',
        'sks',
        'rpl_type',
        'status',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /*
    | Relations
    */

    public function studyPrograms()
    {
        return $this->belongsToMany(
            StudyProgram::class
        );
    }

    /*
    | Accessors
    */

    public function getStatusAttribute(): string
    {
        return $this->is_active
            ? 'active'
            : 'inactive';
    }
}