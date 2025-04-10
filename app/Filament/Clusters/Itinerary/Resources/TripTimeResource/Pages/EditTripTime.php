<?php

namespace App\Filament\Clusters\Itinerary\Resources\TripTimeResource\Pages;

use App\Filament\Clusters\Itinerary\Resources\TripResource;
use App\Filament\Clusters\Itinerary\Resources\TripTimeResource;
use Filament\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class EditTripTime extends EditRecord
{
    protected static string $resource = TripTimeResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


}
