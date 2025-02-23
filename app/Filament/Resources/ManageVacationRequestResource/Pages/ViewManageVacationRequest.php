<?php

namespace App\Filament\Resources\ManageVacationRequestResource\Pages;

use App\Filament\Resources\ManageVacationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewManageVacationRequest extends ViewRecord
{
    protected static string $resource = ManageVacationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
