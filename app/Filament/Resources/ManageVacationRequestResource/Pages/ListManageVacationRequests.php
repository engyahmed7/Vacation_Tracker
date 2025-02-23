<?php

namespace App\Filament\Resources\ManageVacationRequestResource\Pages;

use App\Filament\Resources\ManageVacationRequestResource;
use Filament\Resources\Pages\ListRecords;
use App\Enums\StatusEnum;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListManageVacationRequests extends ListRecords
{
    protected static string $resource = ManageVacationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }


    protected function getTableQuery(): Builder
    {
        $user = Auth::user();
        return parent::getTableQuery()
            ->where('status', StatusEnum::PENDING->value)
            ->when($user->hasRole('hr'), function ($query) {
                $query->whereNull('hr_id');
            })
            ->when($user->hasRole('supervisor'), function ($query) {
                $query->whereNull('supervisor_id');
            });
    }
}
