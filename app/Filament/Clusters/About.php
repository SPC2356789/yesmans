<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class About extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = '網頁設定';//群組
    protected static ?int $navigationSort = 1;
    protected static ?string $title = '關於我們';

    public static function getClusterBreadcrumb(): string
    {
        return self::$title;
    }

    public static function getNavigationLabel(): string
    {
        return self::$title;
    }
}
