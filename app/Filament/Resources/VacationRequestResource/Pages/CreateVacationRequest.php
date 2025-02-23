<?php
namespace App\Filament\Resources\VacationRequestResource\Pages;

use App\Filament\Resources\VacationRequestResource;
use App\Models\VacationRequest;
use Filament\Resources\Pages\CreateRecord;
use App\Notifications\MattermostNotification;

class CreateVacationRequest extends CreateRecord
{
    protected static string $resource = VacationRequestResource::class;

    protected function afterCreate(): void
    {
        // Get the newly created vacation request
        $vacationRequest = $this->record;
      
        $data = ["text" => "A New Vacation Request has been created by {$vacationRequest->user->name}.",
            "attachments" => [
                [
                    "author_name" => $vacationRequest->user->name,
                    "title" => "[Request #{$vacationRequest->id}]",
                    "text" => "Vacation Type: {$vacationRequest->vacation_type_id->getLabel()}\nStart Date: {$vacationRequest->start_date}\nEnd Date: {$vacationRequest->end_date}\nComment: {$vacationRequest->comment}"
                ]
            ]
        ];
        $vacationRequest->user->notify(new MattermostNotification($data,$channel = 'vacation-tracker-dev'));
    }
}

