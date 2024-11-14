<?php

namespace App\Filament\Clusters\Blogs\Resources\BlogCategoryResource\Pages;

use App\Filament\Clusters\Blogs\Resources\BlogCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlogCategory extends EditRecord
{
    protected static string $resource = BlogCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
