<?php

namespace App\Filament\Resources;

use App\Enums\StatusEnum;
use App\Filament\Resources\ManageVacationRequestResource\Pages;
use App\Models\VacationRequest;
use App\Services\VacationService;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ManageVacationRequestResource extends VacationRequestResource
{
    protected static ?string $model = VacationRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->disabled(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->disabled(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->disabled(),
                Tables\Columns\TextColumn::make('vacation_type_id')
                    ->label('Vacation Type'),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->disabled(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->disabled(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->disabled(),
            ])
            ->actions([
                Tables\Actions\Action::make('delete')
                    ->authorize(function (VacationRequest $record) {
                        $now = now();
                        return (Auth::user()->hasRole('admin'))
                            && ($record->status->value === StatusEnum::PENDING->value || $record->status->value === StatusEnum::APPROVED->value)
                            && ($record->start_date > $now);
                    })
                    ->action(
                        function (VacationRequest $request) {
                            $service = app(VacationService::class);
                            $request->status->value = StatusEnum::REJECTED;
                            $service->adjustVacationBalance($request);
                            $request->save();
                        }
                    )
                    ->label('Cancel')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->action(function (VacationRequest $record) {
                        $service = app(VacationService::class);

                        $daysRequested = $record->end_date->diffInDays($record->start_date) + 1;

                        if (!$service->hasSufficientBalance($record->user_id, $record->vacation_type_id, $daysRequested)) {
                            return;
                        }

                        if (Auth::user()->hasRole('hr')) {
                            $record->update([
                                'hr_id' => Auth::id(),
                            ]);
                        } elseif (Auth::user()->hasRole('supervisor')) {
                            $record->update([
                                'supervisor_id' => Auth::id(),
                            ]);
                        }

                        if ($record->hr_id && $record->supervisor_id) {
                            $record->update(['status' => StatusEnum::APPROVED->value]);
                            $service->adjustVacationBalance($record);
                            $service->MattermostNotification('Vacation Approved For ', $record, 'vacation-tracker-dev');
                        }
                    })
                    ->icon('heroicon-o-check-circle')
                    ->button()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListManageVacationRequests::route('/'),
            'create' => Pages\CreateManageVacationRequest::route('/create'),
            'view' => Pages\ViewManageVacationRequest::route('/{record}'),
            'edit' => Pages\EditManageVacationRequest::route('/{record}/edit'),
        ];
    }
}
