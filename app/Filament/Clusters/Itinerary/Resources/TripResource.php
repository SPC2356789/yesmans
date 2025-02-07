<?php

namespace App\Filament\Clusters\Itinerary\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Clusters\Itinerary;

use App\Filament\Clusters\Itinerary\Resources\TripResource\Pages;
use App\Filament\Clusters\Itinerary\Resources\TripResource\RelationManagers;
use App\Forms\Components\MediaPicker;
use App\Models\Media;
use App\Models\Categories;
use App\Models\Trip;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\Curator\Components\Forms\CuratorPicker;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Itinerary::class;
    protected static ?string $category = Categories::class;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Select::make('category')
                    ->label('行程分類')
                    ->options(self::$category::getData(2, 1)->where('orderby', '>', 0)->pluck('name', 'id'))// 從分類模型中獲取選項
                    ->searchable() // 支持搜索
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subtitle')
                    ->maxLength(255),
                MediaPicker::make('carousel')
                    ->label('選擇照片')
                    ->multiple()
                    ->options(Media::getMedia())
                    ->searchable()
                    ->helperText('超過10張會拖效能')
                    ->columnSpanFull()
                    ->allowHtml(),
                Select::make('icon')
                    ->label('選擇標籤')
                    ->options(Media::getMedia('icon', 10))
                    ->searchable()
                    ->allowHtml(),
                Forms\Components\select::make('tags')
                    ->options(self::$category::getData(2, 2,"name,id"))// 從分類模型中獲取選項
                    ->multiple()
                    ->searchable() // 支持搜索
                    ->required(),
                Forms\Components\TextInput::make('quota')
                    ->numeric(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Section::make([
                    TinyEditor::make('content')
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsVisibility('public')
                        ->fileAttachmentsDirectory(
                            function ($record) {
                                $base = '/Itinerary/trip/';
                                if (!$record) {
                                    return $base . now()->format('Ym'); // 使用當前時間
                                } else {
                                    return $base . $record->created_at->format('Ymd'); // 使用創建時間
                                }
                            }
                        )
                        ->profile('aal')
                        ->ltr() // Set RTL or use->direction('auto|rtl|ltr')
                        ->columnSpan('full')
                        ->label('行程內容')
                        ->required(),
                ]),
                Forms\Components\Textarea::make('agreement_content')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_published')
                    ->required(),
//                Forms\Components\TextInput::make('orderby')
//                    ->numeric(),
                Forms\Components\TextInput::make('seo_title')
                    ->maxLength(255),
                Forms\Components\Textarea::make('seo_description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('categories.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtitle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quota')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('TWD') // 顯示台幣格式
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('orderby')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('seo_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    protected static ?string $title = '行程模板';

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrip::route('/create'),
            'edit' => Pages\EditTrip::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
