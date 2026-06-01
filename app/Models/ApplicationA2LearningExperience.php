<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationA2LearningExperience extends Model
{
    use HasFactory;

    protected $fillable = [

        'application_id',

        'title',

        'experience_type',

        'organization_name',

        'start_date',

        'end_date',

        'is_ongoing',

        'description',
    ];

    protected function casts(): array
    {
        return [

            'start_date'
                => 'date',

            'end_date'
                => 'date',

            'is_ongoing'
                => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function application()
    {
        return $this->belongsTo(
            Application::class
        );
    }
}