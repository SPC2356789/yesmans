<?php

namespace App\Filament\Clusters\Itinerary\Resources;

use App\Filament\Clusters\Itinerary;
use App\Models\BlogItem;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use App\Filament\Pages\SettingsN as BaseSettings;
use Filament\Forms\Components\Builder;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Split;


class ItineraryBase extends BaseSettings
{

    protected static ?string $cluster = Itinerary::class;

    protected static ?string $title = '基礎設定';
    protected static ?string $area = 'index';
    protected static ?int $navigationSort = 3;

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
                            TextInput::make(self::$area . '_seo.title')
                                ->label('主題')
                                ->required(),
                            Repeater::make(self::$area . '_hot')
                                ->label('熱門文章')
                                ->schema([
                                    Select::make('blogItem')
                                        ->label('選擇文章')
                                        ->options(
                                            BlogItem::SelectDataImg()
                                        )
                                        ->searchable()
                                        ->distinct()
                                        ->helperText('每篇文章只能選一次')
                                        ->selectablePlaceholder(false)
                                        ->allowHtml(),
                                ])
                                ->defaultItems(4)
                                ->deletable(false)
                                ->grid(4)
                                ->minItems(4)
                                ->maxItems(4)
                                ->reorderableWithButtons(),
                            TextInput::make(self::$area . '_seo.description')
                                ->label('介紹')
                                ->required(),
                            TextInput::make(self::$area . '_OG.title')
                                ->label('OG標題')
                                ->placeholder('標題不要超過 25 – 30 個中文字')
                                ->required(),
                            FileUpload::make(self::$area . '_OG.image')
                                ->label('OG圖片上傳')
                                ->image()
                                ->imageEditor(),
                            Select::make(self::$area . '_seo.robots')
                                ->label('索引')
                                ->options([
                                    'index, follow' => 'Index, Follow',
                                    'index, nofollow' => 'Index, Nofollow',
                                    'noindex, follow' => 'Noindex, Follow',
                                    'noindex, nofollow' => 'Noindex, Nofollow',
                                ]),
                        ]),


                ]),
        ];
    }
}
