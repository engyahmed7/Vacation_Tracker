<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Enums\VacationTypesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Events\VacationRequestCreated;
class VacationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'comment',
        'supervisor_id',
        'hr_id',
        'status',
        'vacation_type_id',
    ];
    

    protected $casts = [
        'status'=> StatusEnum::class,
        'vacation_type_id'=>VacationTypesEnum::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Set the 'user_id' attribute to the ID of the currently logged-in user
            if (Auth::check()) {
                $model->user_id = Auth::id(); // Or use auth()->id()
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function hr(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hr_id');
    }

    public function vacationType():BelongsTo
    {
        return $this->belongsTo(VacationType::class);
    }

}