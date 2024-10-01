<?php

namespace App\Filament\Clusters\IndexSetting\Resources;

use App\Filament\Clusters\IndexSetting;
use Closure;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

//use App\Models\Fact;

class IndexBase extends BaseSettings
{

//    protected static ?string $model = IndexBase::class;  修改存取模型
    protected static ?string $cluster = IndexSetting::class;
    public function title()
    {
        return '基礎設定';
    }

    public function schema(): array|Closure
    {
        return [
            Tabs::make('Settings')
                ->schema([
                    Tabs\Tab::make('Seo')
                        ->schema([
                            TextInput::make('seo.title')
                                ->required(),
                            TextInput::make('seo.description')
                                ->required(),
                        ]),
                    Tabs\Tab::make('一般設定')
                        ->schema([
                            TextInput::make('general.brand_name')
                                ->required(),
                        ]),
                ]),
        ];
    }

    public function getTitle(): string//標題
    {
        return (new static())->title();
    }

    public static function getNavigationLabel(): string//集群標題
    {
        return (new static())->title();
    }

}
