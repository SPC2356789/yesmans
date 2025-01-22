<?php

namespace App\Filament\Clusters\Itinerary\Resources\ItryCategoryResource\Pages;

use App\Filament\Clusters\Itinerary\Resources\ItryCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageItryCategories extends ManageRecords
{
    protected static string $resource = ItryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {

                    $data['area'] = 2;
                    $data['type'] = 1;
                    return $data;
                }),
        ];
    }

}
