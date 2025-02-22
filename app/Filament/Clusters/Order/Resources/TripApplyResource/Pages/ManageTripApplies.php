<?php

namespace App\Filament\Clusters\Order\Resources\TripApplyResource\Pages;

use App\Filament\Clusters\Order\Resources\TripApplyResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTripApplies extends ManageRecords
{
    protected static string $resource = TripApplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
