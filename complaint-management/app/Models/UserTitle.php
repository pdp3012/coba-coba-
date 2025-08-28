<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTitle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'min_complaints',
        'max_complaints',
        'color',
        'description',
    ];

    protected $casts = [
        'min_complaints' => 'integer',
        'max_complaints' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the title for a given complaint count.
     */
    public static function getTitleForComplaintCount($count)
    {
        return self::where('min_complaints', '<=', $count)
                   ->where(function($query) use ($count) {
                       $query->where('max_complaints', '>=', $count)
                             ->orWhereNull('max_complaints');
                   })
                   ->orderBy('min_complaints', 'desc')
                   ->first();
    }
}
