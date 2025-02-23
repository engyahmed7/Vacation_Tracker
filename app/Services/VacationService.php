<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Enums\VacationTypesEnum;
use App\Models\VacationRequest;
use App\Models\VacationBalance;
use App\Notifications\MattermostNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VacationService
{
    public function adjustVacationBalance(VacationRequest $vacationRequest)
    {
        DB::transaction(function () use ($vacationRequest) {
            $vacationBalance = VacationBalance::where('user_id', $vacationRequest->user_id)
                ->where('vacation_type_id', $vacationRequest->vacation_type_id)
                ->first();

            if (!$vacationBalance) {
                return;
            }

            $daysRequested = $vacationRequest->end_date->diffInDays($vacationRequest->start_date) + 1;

            $originalStatusEnum = $vacationRequest->getOriginal('status');
            $originalStatus = $originalStatusEnum instanceof StatusEnum ? $originalStatusEnum->value : $originalStatusEnum;

            $currentStatus = $vacationRequest->status->value ?? null;

            if ($currentStatus === StatusEnum::APPROVED->value) {
                $vacationBalance->used_days += $daysRequested;
                $vacationBalance->remaining_days = $vacationBalance->total_days - $vacationBalance->used_days;
            } elseif ($currentStatus === StatusEnum::REJECTED->value && $originalStatus === StatusEnum::APPROVED->value) {
                $vacationBalance->used_days -= $daysRequested;
                $vacationBalance->remaining_days = $vacationBalance->total_days - $vacationBalance->used_days;
            }

            if ($vacationBalance->used_days < 0) {
                $vacationBalance->used_days = 0;
            }

            if ($vacationBalance->remaining_days < 0) {
                $vacationBalance->remaining_days = 0;
            }

            $vacationBalance->save();
        });
    }


    public function hasSufficientBalance(int $userId, VacationTypesEnum $vacationTypeId, int $daysRequested): bool
    {
        $vacationBalance = VacationBalance::where('user_id', $userId)
            ->where('vacation_type_id', $vacationTypeId->value)
            ->first();

        if (!$vacationBalance) {
            return false;
        }

        return $vacationBalance->remaining_days >= $daysRequested;
    }

    public function MattermostNotification($message, $vacationRequest, $channel): void
    {

        $data = [
            "text" => "{$message} {$vacationRequest->user->name}.",
            "attachments" => [
                [
                    "author_name" => $vacationRequest->user->name,
                    "title" => "[Request #{$vacationRequest->id}]",
                    "text" => "\nStart Date: {$vacationRequest->start_date}\nEnd Date: {$vacationRequest->end_date}\nComment: {$vacationRequest->comment}"
                ]
            ]
        ];
        Log::info($vacationRequest->user);
        $vacationRequest->user->notify(new MattermostNotification($data, $channel));
    }
}
