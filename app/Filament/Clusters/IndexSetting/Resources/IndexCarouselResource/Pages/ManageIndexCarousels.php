<?php

namespace App\Filament\Clusters\IndexSetting\Resources\IndexCarouselResource\Pages;

use App\Filament\Clusters\IndexSetting\Resources\IndexCarouselResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageIndexCarousels extends ManageRecords
{
    protected static string $resource = IndexCarouselResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
