<?php

namespace App\Filament\Clusters\Blogs\Resources\BlogItemResource\Pages;

use App\Filament\Clusters\Blogs\Resources\BlogItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlogItems extends ListRecords
{
    protected static string $resource = BlogItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
