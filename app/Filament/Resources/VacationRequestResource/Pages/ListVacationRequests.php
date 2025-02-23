<?php

namespace App\Filament\Resources\VacationRequestResource\Pages;

use App\Filament\Resources\VacationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVacationRequests extends ListRecords
{
    protected static string $resource = VacationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

}