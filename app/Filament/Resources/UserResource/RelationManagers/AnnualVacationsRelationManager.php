<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\StatusEnum;
use App\Models\VacationRequest;
use App\Services\VacationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AnnualVacationsRelationManager extends RelationManager
{
    protected static string $relationship = 'annualVacations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('annual_vacation')
                    ->label('Annual Vacation')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('annual_vacation')
            ->columns([
                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->default('-'),
                TextColumn::make('end_date')
                    ->label('End Date')
                    ->default('-'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->default('-'),
                TextColumn::make('comment')
                    ->default('-')
                    ->limit(20)
                    ->words(5)
                    ->tooltip(function (TextColumn $component): ?string {
                        $state = $component->getState();

                        if (strlen($state) <= $component->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),
                TextColumn::make('supervisor.name')
                    ->default('-')
                    ->sortable()
                    ->label('Supervisor Approval'),
                TextColumn::make('hr.name')
                    ->default('-')
                    ->sortable()
                    ->label('Hr Approval'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->authorize(function (VacationRequest $record) {
                        $now = now();
                        return (Auth::id() === $record->user_id || Auth::user()->hasRole('admin'))
                            && $record->status->value === StatusEnum::PENDING->value
                            && ($record->start_date > $now);
                    })
                    ->icon('heroicon-o-pencil')
                    ->button()
                    ->color('primary'),
                Tables\Actions\DeleteAction::make()
                    ->authorize(function (VacationRequest $record) {
                        $now = now();
                        return (Auth::id() === $record->user_id || Auth::user()->hasRole('admin'))
                            && ($record->status->value === StatusEnum::PENDING->value || $record->status->value === StatusEnum::APPROVED->value)
                            && ($record->start_date > $now);
                    })
                    ->action(
                        function (VacationRequest $request) {
                            $service = app(VacationService::class);

                            if ($request->status->value === StatusEnum::APPROVED->value) {
                                $request->status = StatusEnum::REJECTED;
                                $service->adjustVacationBalance($request);
                            }

                            $request->delete();
                        }
                    )

                    ->label('Cancel')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->button()
                    ->icon('heroicon-o-trash'),

            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        $hasVacation = $ownerRecord->annualVacations()->exists();

        return !empty($hasVacation);
    }
}
