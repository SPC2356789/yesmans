<?php

namespace App\Filament\Clusters\Order\Resources\TripOrderResource\Pages;

use App\Filament\Clusters\Order\Resources\TripOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTripOrders extends ListRecords
{
    protected static string $resource = TripOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
