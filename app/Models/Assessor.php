<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessor extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'phone',
        'address',
        'email_verified_at',
        'is_active',
    ];

    /**
     * User relation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //Assessment Relation
    public function assessments(): HasMany
    {
        return $this->hasMany(
            Assessment::class
        );
    }
}