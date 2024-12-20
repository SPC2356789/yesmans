<?php

namespace App\Filament\Clusters\Blogs\Resources\BlogItemResource\Pages;

use App\Filament\Clusters\Blogs\Resources\BlogItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogItem extends CreateRecord
{
    protected static string $resource = BlogItemResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                #20241220
                ->mutateFormDataUsing(function (array $data): array {
                    $data['is_published'] = 0;
                    return $data;
                }),
        ];
    }
}
