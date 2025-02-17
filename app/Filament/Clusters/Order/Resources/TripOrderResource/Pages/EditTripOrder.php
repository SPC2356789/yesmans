<?php

namespace App\Filament\Clusters\Order\Resources\TripOrderResource\Pages;

use App\Filament\Clusters\Order\Resources\TripOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTripOrder extends EditRecord
{
    protected static string $resource = TripOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
