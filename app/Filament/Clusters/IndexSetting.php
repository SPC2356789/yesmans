<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class IndexSetting extends Cluster
{
//    protected static ?string $clusterBreadcrumb = null;


    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = '網頁設定';//群組
    public function title(): string
    {
        return '首頁設定';
    }

    public static function getClusterBreadcrumb(): string
    {
        return (new static())->title();
    }
    public static function getNavigationLabel(): string
    {
        return (new static())->title();
    }
}
