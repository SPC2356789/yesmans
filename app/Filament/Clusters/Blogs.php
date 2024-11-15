<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Blogs extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = '網頁設定';//群組
    protected static ?int $navigationSort = 1;
    protected static ?string $title = '文章區';

    public static function getClusterBreadcrumb(): string
    {
        return self::$title;
    }

    public static function getNavigationLabel(): string
    {
        return self::$title;
    }
}
