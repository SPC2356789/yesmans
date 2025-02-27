<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class IndexSetting extends Cluster
{
//    protected static ?string $clusterBreadcrumb = null;


    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = -99;
    protected static ?string $navigationGroup = '網頁設定';//群組
    protected static ?string $title = '首頁設定';

    public static function getClusterBreadcrumb(): string
    {
        return self::$title;
    }

    public static function getNavigationLabel(): string
    {
        return self::$title;
    }
}
