<?php

namespace App\Filament\Clusters\Blogs\Resources\BlogCategoryResource\Pages;

use App\Filament\Clusters\Blogs\Resources\BlogCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlogCategories extends ListRecords
{
    protected static string $resource = BlogCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
