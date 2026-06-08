<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [

        'application_id',

        'assessor_id',

        'notes',

        'recommendation',

        'submitted_at',
    ];

    protected function casts(): array
    {
        return [

            'submitted_at'
                => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function application(): BelongsTo
    {
        return $this->belongsTo(
            Application::class
        );
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(
            Assessor::class,
            'assessor_id'
        );
    }

    public function courseMappings(): HasMany
    {
        return $this->hasMany(
            AssessmentCourseMapping::class
        );
    }
}