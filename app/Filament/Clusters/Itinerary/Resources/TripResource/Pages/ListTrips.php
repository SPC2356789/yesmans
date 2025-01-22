<?php

namespace App\Filament\Clusters\Itinerary\Resources\TripResource\Pages;

use App\Filament\Clusters\Itinerary\Resources\TripResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrips extends ListRecords
{
    protected static string $resource = TripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
