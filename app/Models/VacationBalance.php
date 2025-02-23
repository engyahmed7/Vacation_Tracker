<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vacation_type_id',
        'total_days',
        'used_days',
        'remaining_days',
    ];

    
}