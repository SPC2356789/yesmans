<?php

namespace App\Filament\Clusters\IndexSetting\Resources;

use App\Filament\Clusters\IndexSetting;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;
use Filament\Forms\Components\Builder;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use function Laravel\Prompts\textarea;


class IndexBase extends BaseSettings
{

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
                            TextInput::make('index_seo.title')
                                ->label('網頁標題')
                                ->placeholder('標題不要超過 25 – 30 個中文字')
                                ->required(),
                            TextInput::make('index_seo.description')
                                ->label('介紹')
                                ->placeholder('網頁描述字數控制在 50 – 76 個中文字')
                                ->required(),
                            Textarea::make('index_seo.schema_markup')
                                ->label('結構化資料')
                                ->rows(5)
                                ->placeholder('https://search.google.com/test/rich-results?hl=zh-tw')
                        ]),
                    Tabs\Tab::make('開放圖譜(OpenGraph)')
                        ->schema([
                            TextInput::make('index_OG.title')
                                ->label('OG標題')
                                ->placeholder('標題不要超過 25 – 30 個中文字')
                                ->required(),
                            TextInput::make('index_OG.description')
                                ->label('OG介紹')
                                ->placeholder('網頁描述字數控制在 50 – 76 個中文字')
                                ->required(),
                            FileUpload::make('index_OG.image')
                                ->label('OG圖片上傳')
                                ->image()
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
                                            Split::make([
                                                Section::make([
                                                    TextInput::make('foot.media.title')
                                                        ->label('圖標媒體名稱')
                                                        ->required(),
                                                    TextInput::make('foot.media.url')
                                                        ->label('媒體連結')
                                                        ->required(),
                                                    Toggle::make('foot.media.status')
                                                        ->label('開啟')
                                                        ->onColor('success')
                                                    ,
                                                ]),
                                                Section::make([
                                                    FileUpload::make('foot.media.image')
                                                        ->label('圖標上傳')
                                                        ->image()
                                                ]),
                                            ])->from('md')


                                        ])

                                ]),
                        ]),
                ]),
        ];
    }

}
