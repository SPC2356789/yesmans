<?php

namespace App\Filament\Clusters\Blogs\Resources\BlogCategoryResource\Pages;

use App\Filament\Clusters\Blogs\Resources\BlogCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageBlogCategories extends ManageRecords
{
    protected static string $resource = BlogCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {

                    $data['area'] = 1;
                    $data['type'] = 1;
                    return $data;
                }),
        ];
    }

}
