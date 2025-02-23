<?php

namespace App\Filament\Resources\VacationRequestResource\Pages;

use App\Filament\Resources\VacationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVacationRequest extends EditRecord
{
    protected static string $resource = VacationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
