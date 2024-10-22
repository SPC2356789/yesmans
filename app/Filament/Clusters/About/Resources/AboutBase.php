<?php

namespace App\Filament\Clusters\About\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Clusters\About;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

class AboutBase extends BaseSettings
{

    protected static ?string $cluster = About::class;

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
                            TextInput::make('about_seo.title')
                                ->label('主題')
                                ->required(),
                            TextInput::make('about_seo.description')
                                ->label('介紹')
                                ->required(),
                            Textarea::make('about_seo.schema_markup')
                                ->label('結構化資料')
                                ->rows(5)
                                ->placeholder('https://search.google.com/test/rich-results?hl=zh-tw'),
                            TextInput::make('about_OG.title')
                                ->label('OG標題')
                                ->placeholder('標題不要超過 25 – 30 個中文字')
                                ->required(),
                            FileUpload::make('about_OG.image')
                                ->label('OG圖片上傳')
                                ->image()
                                ->imageEditor(),
                            Select::make('about_seo.robots')
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
                                    TextInput::make('about.title')
                                        ->label('故事標題')
                                        ->required(),
                                    TextInput::make('about.imgAlt')
                                        ->label('圖片描述')
                            ]),
                            FileUpload::make('about.image')
                                ->label('故事圖片')
                                ->image()
                                ->imageEditor(),
                            TinyEditor::make('about.introduce')
                                ->fileAttachmentsDisk('public')
                                ->fileAttachmentsVisibility('public')
                                ->fileAttachmentsDirectory('uploads')
                                ->profile('aal')
                                ->ltr() // Set RTL or use->direction('auto|rtl|ltr')
                                ->columnSpan('full')
                                ->label('關於我們故事內容')
                                ->required(),
                        ]),
                ]),
        ];
    }

}
