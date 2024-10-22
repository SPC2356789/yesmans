<?php

namespace App\Filament\Resources\RlResource\Pages;

use App\Filament\Resources\RlResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewRoles extends ListRecords
{
    protected static string $resource = RlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
