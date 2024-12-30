<?php

namespace App\Filament\Clusters\About\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Clusters\About;
use Closure;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

use Filament\Forms\Components\Toggle;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

class AboutBase extends BaseSettings
{

    protected static ?string $cluster = About::class;

    protected static ?string $title = '基礎設定';
    protected static ?string $area ='about';

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
                            TextInput::make(self::$area.'_seo.title')
                                ->label('主題')
                                ->required(),
                            TextInput::make(self::$area.'_seo.description')
                                ->label('介紹')
                                ->required(),
                            Textarea::make(self::$area.'_seo.schema_markup')
                                ->label('結構化資料')
                                ->rows(5)
                                ->placeholder('https://search.google.com/test/rich-results?hl=zh-tw'),
                            TextInput::make(self::$area.'_OG.title')
                                ->label('OG標題')
                                ->placeholder('標題不要超過 25 – 30 個中文字')
                                ->required(),
                            FileUpload::make(self::$area.'_OG.image')
                                ->label('OG圖片上傳')
                                ->image()
                                ->imageEditor(),
                            Select::make(self::$area.'_seo.robots')
                                ->label('索引')
                                ->options([
                                    'index, follow' => 'Index, Follow',
                                    'index, nofollow' => 'Index, Nofollow',
                                    'noindex, follow' => 'Noindex, Follow',
                                    'noindex, nofollow' => 'Noindex, Nofollow',
                                ]),
                        ]),
                    Tabs\Tab::make('關於我們(About)')
                        ->schema([

                            Split::make([
                                TextInput::make(self::$area.'.title')
                                    ->label('關於我們標題')
                                    ->required(),
                                TextInput::make(self::$area.'.imgAlt')
                                    ->label('圖片描述')
                            ]),
                            FileUpload::make(self::$area.'.image')
                                ->label('關於我們圖片')
                                ->directory(self::$area.'') // 指定儲存的目錄
                                ->image()
                                ->imageEditor(),
                            TinyEditor::make(self::$area.'.introduce')
                                ->fileAttachmentsDisk('public/About')
                                ->fileAttachmentsVisibility('public/About')
                                ->fileAttachmentsDirectory('uploads')
                                ->profile('aal')
                                ->ltr() // Set RTL or use->direction('auto|rtl|ltr')
                                ->columnSpan('full')
                                ->label('關於我們內容')
                                ->required(),
                        ]),
                    Tabs\Tab::make('故事(story)')
                        ->schema([
                            Builder::make(self::$area.'.story')
                                ->label('新增故事')
                                ->blocks([
                                    Builder\Block::make(self::$area.'.storyData')
                                        ->label('新增篇章')
                                        ->schema([
                                            Split::make([
                                                Section::make([
                                                    TextInput::make('title')
                                                        ->label('篇章標題')
                                                        ->required(),
                                                    Textarea::make('content')
                                                        ->label('篇章內容')
                                                        ->rows(5)        // 設置顯示的行數
                                                        ->default('請輸入內容...') // 可選：設定預設值
                                                        ->placeholder('在這裡輸入篇章內容...') // 可選：設定佔位符
                                                        ->required(),
                                                    Toggle::make('status')
                                                        ->label('開啟')
                                                        ->onColor('success')
                                                    ,
                                                ]),
                                                Section::make([
                                                    FileUpload::make('image')
                                                        ->label('篇章圖片上傳')
                                                        ->directory(self::$area.'/Story')
                                                        ->image()
                                                        ->imageEditor(),
                                                    TextInput::make('alt')
                                                        ->label('描述'),
                                                ]),
                                            ])->from('md')


                                        ])


                                ]),
                        ]),
                ]),
        ];
    }

}
