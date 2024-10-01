<?php

namespace App\Filament\Clusters\IndexSetting\Resources;

use App\Filament\Clusters\IndexSetting;
use App\Filament\Clusters\IndexSetting\Resources\IndexCarouselResource\Pages;
use App\Filament\Clusters\IndexSetting\Resources\IndexCarouselResource\RelationManagers;
use App\Models\IndexCarousel;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndexCarouselResource extends Resource
{
    protected static ?string $model = IndexCarousel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = IndexSetting::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                FileUpload::make('image_path')
                    ->label('首圖跑馬燈')
                    ->image()
                    ->storeFileNamesIn('original_image_names')
                    ->imageEditor(),
                Toggle::make('status')
                    ->onColor('success')
                    ->offColor('danger'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('orderby')
            ->defaultSort('orderby', 'asc')
            ->columns([

                Tables\Columns\ImageColumn::make('image_path')
                    ->label('圖片'),
                Tables\Columns\ToggleColumn::make('status')
                    ->label('顯示'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageIndexCarousels::route('/'),
        ];
    }
}
