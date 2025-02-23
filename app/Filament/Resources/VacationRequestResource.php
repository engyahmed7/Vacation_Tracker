<?php

namespace App\Filament\Resources;

use App\Enums\StatusEnum;
use App\Enums\VacationTypesEnum;
use App\Filament\Resources\VacationRequestResource\Pages;
use App\Filament\Resources\VacationRequestResource\Pages\ViewVacationRequest;
use App\Filament\Resources\VacationRequestResource\RelationManagers;
use App\Models\User;
use App\Models\VacationRequest;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Notifications\MattermostNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VacationRequestResource extends Resource
{
    protected static ?string $model = VacationRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        StatusEnum::PENDING->value => StatusEnum::PENDING->getLabel(),
                        StatusEnum::APPROVED->value => StatusEnum::APPROVED->getLabel(),
                        StatusEnum::REJECTED->value => StatusEnum::REJECTED->getLabel()
                    ])
                    ->required(),
                Select::make('vacation_type_id')
                    ->label('Vacation Type')
                    ->options([
                        VacationTypesEnum::CASUAL->value => VacationTypesEnum::CASUAL->getLabel(),
                        VacationTypesEnum::ANNUAL->value => VacationTypesEnum::ANNUAL->getLabel()
                    ]),
                Select::make('supervisor_id')
                    ->label('Supervisor')
                    ->options(User::role('supervisor')->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                Select::make('hr_id')
                    ->label('HR')
                    ->options(User::role('hr')->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                Forms\Components\Textarea::make('comment')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->default('-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->default('-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->default('-')
                    ->badge(),
                Tables\Columns\TextColumn::make('vacation_type_id')
                    ->label('Vacation Type')
                    ->default('-'),
                Tables\Columns\TextColumn::make('user.name')
                    ->default('-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('supervisor.name')
                    ->default('-')
                    ->sortable()
                    ->label('Supervisor Approval'),
                Tables\Columns\TextColumn::make('hr.name')
                    ->default('-')
                    ->sortable()
                    ->label('Hr Approval'),
                TextColumn::make('comment')
                    ->default('-')
                    ->limit(20)
                    ->words(5)
                    ->tooltip(function (TextColumn $component): ?string {
                        $state = $component->getState();
                 
                        if (strlen($state) <= $component->getCharacterLimit()) {
                            return null;
                        }
                 
                        // Only render the tooltip if the entry contents exceeds the length limit.
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->authorize(fn() => Auth::user()->hasRole('admin')),
                    DeleteAction::make()
                        ->authorize(fn() => Auth::user()->hasRole('admin'))
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('heroicon-o-trash'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVacationRequests::route('/'),
            'view' => ViewVacationRequest::route('/{record}/view'),
            'create' => Pages\CreateVacationRequest::route('/create'),
            'edit' => Pages\EditVacationRequest::route('/{record}/edit'),
        ];
    }
}