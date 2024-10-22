<?php

namespace App\Filament\Resources\UsResource\Pages;

use App\Filament\Resources\UsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUs extends ListRecords
{
    protected static string $resource = UsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
