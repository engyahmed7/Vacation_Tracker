<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VacationType;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use App\Policies\VacationTypePolicy;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\VacationTypeResource\Pages;

class VacationTypeResource extends Resource
{
    protected static ?string $model = VacationType::class;
    protected static ?string $policy = VacationTypePolicy::class;
    protected static ?string $navigationGroup = 'Vacation';
    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make()
                ->schema([
                TextInput::make('name')
                ->required(),

                TextInput::make('total_days')
                ->required()
                ->numeric()
                ->columnSpan(2)
                ])
                ->columns(2)
                ->columnSpan(8)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->searchable(),
                TextColumn::make('total_days')
                ->numeric()
                ->searchable(),
                TextColumn::make('creator.name')
                ->label('Created By')
                ->sortable()
                ->searchable(),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            'index' => Pages\ListVacationTypes::route('/'),
            'create' => Pages\CreateVacationType::route('/create'),
            'edit' => Pages\EditVacationType::route('/{record}/edit'),
        ];
    }

}
