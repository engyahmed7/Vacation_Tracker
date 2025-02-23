<?php

namespace App\Filament\Resources\UserProfileResource\Pages;

use App\Enums\VacationTypesEnum;
use App\Filament\Resources\ManageVacationRequestResource;
use App\Filament\Resources\UserProfileResource;
use App\Filament\Resources\VacationRequestResource;
use App\Models\User;
use App\Models\VacationRequest;
use App\Services\VacationService;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ViewUserProfile extends ViewRecord
{
    protected static string $resource = UserProfileResource::class;



    public function getHeading(): string
    {
        return 'Welcome ' . $this->record->name;
    }


    public function getBreadcrumbs(): array
    {
        return [];
    }


    protected function getHeaderActions(): array
    {
        $actions = [
            // Actions\EditAction::make(),
        ];


        $actions[] = Actions\Action::make('sendVacationRequest')
            ->label('Send Vacation Request')
            ->color('primary')
            ->icon('heroicon-o-plus')
            ->form([
                Grid::make(3)
                    ->schema([
                        DatePicker::make('start_date')
                            ->required()
                            ->label('Start Date'),
                        DatePicker::make('end_date')
                            ->required()
                            ->label('End Date'),
                        Select::make('vacation_type_id')
                            ->options([
                                '1' => 'Casual',
                                '2' => 'Annual',
                            ])
                            ->required()
                            ->label('Vacation Type'),
                    ]),
                Textarea::make('comment')
                    ->label('Comment')
                    ->columnSpan(2),
            ])
            ->action(function (array $data) {
                $daysRequested = (new \Carbon\Carbon($data['end_date']))
                    ->diffInDays(new \Carbon\Carbon($data['start_date'])) + 1;

                $vacationService = app(VacationService::class);
                $vacationTypeId = VacationTypesEnum::from($data['vacation_type_id']);


                if (!$vacationService->hasSufficientBalance(Auth::id(), $vacationTypeId, $daysRequested)) {
                    Notification::make()
                        ->title('Not enough vacation days')
                        ->danger()
                        ->body('You do not have enough remaining vacation days.')
                        ->send();
                    return;
                }

                $vacationRequest = VacationRequest::create(array_merge($data, ['user_id' => Auth::id()]));
                $vacationService->MattermostNotification('A New Vacation Request has been created by ', $vacationRequest, 'vacation-tracker-dev');

                Notification::make()
                    ->title('Vacation request sent')
                    ->success()
                    ->body('Your vacation request has been sent.')
                    ->send();
            })
            ->modalHeading('Create Vacation Request')
            ->modalDescription('Fill in the details to submit a vacation request.')
            ->modalSubmitActionLabel('Submit')
            ->modalIcon('heroicon-o-calendar');

        if (Gate::allows('viewUserFormButtons', auth()->user())) {

            $actions[] = Actions\Action::make('manageVacationRequests')
                ->label('Manage Vacation Requests')
                ->action(fn() => $this->redirect(ManageVacationRequestResource::getUrl('index')))
                ->color('primary');
        }

        return $actions;
    }
}
