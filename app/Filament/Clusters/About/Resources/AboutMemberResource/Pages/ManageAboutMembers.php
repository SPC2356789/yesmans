<?php

namespace App\Filament\Clusters\About\Resources\AboutMemberResource\Pages;

use App\Filament\Clusters\About\Resources\AboutMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAboutMembers extends ManageRecords
{
    protected static string $resource = AboutMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
