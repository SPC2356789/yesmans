<?php

namespace App\Filament\Clusters\IndexSetting\Resources;

use App\Filament\Clusters\IndexSetting;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Table;
use Filament\Tables;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;
use Illuminate\Support\Facades\App;
use Filament\Forms\Components\Builder;

//+欄位
//use App\Models\Fact;

class IndexBase extends BaseSettings
{

//    protected static ?string $model = IndexBase::class;  修改存取模型
    protected static ?string $cluster = IndexSetting::class;

    protected static ?string $title = '基礎設定';


    public static function getModelLabel(): string
    {
        return self::$title;
    }

    public function getTitle(): string//標題
    {
        return self::$title;
    }

    public static function getNavigationLabel(): string//集群標題
    {
        return self::$title;
    }

    public function schema(): array|Closure
    {
        return [
            Tabs::make('Settings')
                ->schema([
                    Tabs\Tab::make('關鍵字(Seo)')
                        ->schema([
                            TextInput::make('seo.title')
                                ->label('主題')
                                ->required(),
                            TextInput::make('seo.description')
                                ->label('介紹')
                                ->required(),
                        ]),
                    Tabs\Tab::make('一般設定(general)')
                        ->schema([
                            TextInput::make('general.brand_name')
                                ->label('品牌名稱')
                                ->required(),
                        ]),
                    Tabs\Tab::make('頁腳設定(foot)')
                        ->schema([
                            Builder::make('foot.media')
                                ->label('頁腳媒體圖標設定')
                                ->blocks([
                                    Builder\Block::make('foot.media')
                                        ->label('新增媒體圖標')
                                        ->schema([
                                            TextInput::make('foot.media.title')
                                                ->label('圖標媒體名稱')
                                                ->required(),
                                            Toggle::make('foot.media.status')
                                                ->label('開啟')
                                                ->onColor('success')
                                                ->offColor('danger'),
                                            TextInput::make('foot.media.url')
                                                ->label('媒體連結')
                                                ->required(),
                                            FileUpload::make('foot.media.image')
                                                ->label('圖標上傳')
                                                ->image()
                                        ])
                                        ->columns(2)// 設置為兩列'

                                ]),
                        ]),
                ]),
        ];
    }

}
