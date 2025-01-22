<?php

namespace App\Filament\Clusters\Itinerary\Resources\ItrySpanResource\Pages;

use App\Filament\Clusters\Itinerary\Resources\ItrySpanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageItrySpans extends ManageRecords
{
    protected static string $resource = ItrySpanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {

                    $data['area'] = 2;
                    $data['type'] = 2;
                    return $data;
                }),
        ];
    }
}
