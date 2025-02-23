<?php

namespace App\Filament\Resources\VacationRequestResource\Pages;

use App\Filament\Resources\VacationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVacationRequest extends ViewRecord
{
    protected static string $resource = VacationRequestResource::class;

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
