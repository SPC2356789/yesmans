<?php

namespace App\Filament\Clusters\Itinerary\Resources\TripResource\Pages;

use App\Filament\Clusters\Itinerary\Resources\TripResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTrip extends CreateRecord
{
    protected static string $resource = TripResource::class;
}
