<?php

namespace App\Filament\Clusters\Blogs\Resources\BlogItemResource\Pages;

use App\Filament\Clusters\Blogs\Resources\BlogItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlogItem extends EditRecord
{
    protected static string $resource = BlogItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
