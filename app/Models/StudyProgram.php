<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $fillable = [

        'code',

        'name',

        'total_sks',

        'max_convertible_sks',

        'supports_a1',

        'supports_a2',

        'is_hybrid_allowed',

        'status',
    ];

    /*
    | Relationships
    */

    public function courses()
    {
        return $this->belongsToMany(
            Course::class
        );
    }

    public function assessors()
    {
        return $this->hasMany(
            Assessor::class
        );
    }

    public function applications()
    {
        return $this->hasMany(
            Application::class
        );
    }
}