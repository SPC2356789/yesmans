<?php

namespace App\Filament\Clusters\Blogs\Resources\BlogCategoryResource\Pages;

use App\Filament\Clusters\Blogs\Resources\BlogCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogCategory extends CreateRecord
{
    protected static string $resource = BlogCategoryResource::class;
}
