<?php

namespace App\Filament\Clusters\Itinerary\Resources\TripTimeResource\Pages;

use App\Filament\Clusters\Itinerary\Resources\TripTimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTripTimes extends ManageRecords
{
    protected static string $resource = TripTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                #20241220
                ->mutateFormDataUsing(function (array $data): array {
                    $data['date_start'] = $data['date'][0]??null;
                    $data['date_end'] = $data['date'][1]??null;
                    unset($data['date']);
//                    dd($data);
                    return $data;
                }),

            ];
    }

}
