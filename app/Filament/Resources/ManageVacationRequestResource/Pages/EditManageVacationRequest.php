<?php

namespace App\Filament\Resources\ManageVacationRequestResource\Pages;

use App\Filament\Resources\ManageVacationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManageVacationRequest extends EditRecord
{
    protected static string $resource = ManageVacationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}