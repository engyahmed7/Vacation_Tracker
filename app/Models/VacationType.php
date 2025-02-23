<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VacationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total_days',
        'user_id'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    protected static function booted()
    {
        static::creating(function ($vacation_type) {
            $vacation_type->user_id = Auth::id() ?? 1;
        });
    }
}
