<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ApplicationsResource;
use App\Filament\Resources\ContactsResource;
use App\Filament\Resources\ReservationResource;
use App\Models\Application;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReservation extends ViewRecord
{
    protected static string $resource = ReservationResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('visit')
                ->label('Guest View')
                ->icon('heroicon-m-arrow-top-right-on-square')
                ->openUrlInNewTab()
                ->color('info')
                ->url(function(Reservation $Reservation) {
                    return  route('reservation.detail', $Reservation);
                }),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
