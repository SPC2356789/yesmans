<?php

namespace App\Filament\Clusters\Itinerary\Resources;

use App\Filament\Clusters\Itinerary;
use App\Filament\Clusters\Itinerary\Resources\TripTimeResource\Pages;
use App\Filament\Clusters\Itinerary\Resources\TripTimeResource\RelationManagers;
use App\Models\Trip;
use App\Models\TripTime;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
class TripTimeResource extends Resource
{
    protected static ?string $model = TripTime::class;

    protected static ?string $tripMould = Trip::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Itinerary::class;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Select::make('mould_id')
                    ->options(self::$tripMould::getData())// 從分類模型中獲取選項
                    ->searchable() // 支持搜索
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('NT$'),
                Flatpickr::make('date')
                    ->range()// Use as a Date Range Picker
                    ->required()
                    ,
                Forms\Components\TextInput::make('quota')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('agreement_content')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('food')
                    ->required(),

            ])
            ;

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Trip.title')
                    ->label('模板')
                    ->getStateUsing(function ($record) {
                        return $record->Trip->title . '-' . $record->Trip->subtitle; // 合併 name 和 title
                    }),
//                Tables\Columns\TextColumn::make('mould_id')
//                    ->numeric()
//                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money()
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quota')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('food')
                    ->boolean(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('發布'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTripTimes::route('/'),
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
