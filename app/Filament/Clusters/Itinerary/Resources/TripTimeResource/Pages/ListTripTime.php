<?php

namespace App\Filament\Clusters\Itinerary\Resources\TripTimeResource\Pages;

use App\Filament\Clusters\Itinerary\Resources\TripResource;
use App\Filament\Clusters\Itinerary\Resources\TripTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTripTime extends ListRecords
{
    protected static string $resource = TripTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
