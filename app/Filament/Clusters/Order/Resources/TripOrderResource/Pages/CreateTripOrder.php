<?php

namespace App\Filament\Clusters\Order\Resources\TripOrderResource\Pages;

use App\Filament\Clusters\Order\Resources\TripOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTripOrder extends CreateRecord
{
    protected static string $resource = TripOrderResource::class;
}
