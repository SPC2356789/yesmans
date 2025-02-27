<?php

namespace App\Filament\Clusters\IndexSetting\Resources;

use App\Filament\Clusters\IndexSetting;
use App\Filament\Clusters\IndexSetting\Resources\IndexCarouselResource\Pages;
use App\Filament\Clusters\IndexSetting\Resources\IndexCarouselResource\RelationManagers;
use App\Models\IndexCarousel;
use App\Models\Media;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;

use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Outerweb\FilamentImageLibrary\Filament\Forms\Components\ImageLibraryPicker;
use Outerweb\FilamentImageLibrary\Filament\Infolists\Components\ImageLibraryEntry;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Outerweb\FilamentImageLibrary\Filament\Plugins\FilamentImageLibraryPlugin;
use Outerweb\ImageLibrary\Facades\ImageLibrary;
use Outerweb\ImageLibrary\Entities\ConversionDefinition;
use TomatoPHP\FilamentMediaManager\Form\MediaManagerInput;
use Illuminate\Support\Facades\Storage;
use App\Forms\Components\SelectImg;

class IndexCarouselResource extends Resource
{
    protected static ?string $model = IndexCarousel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = IndexSetting::class;

    protected static ?string $title = '輪播圖';

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image_path')
                    ->label('首圖跑馬燈')
                    ->directory('IndexCarousel') // 指定儲存的目錄
                    ->image()
                    ->storeFileNamesIn('original_image_names')
                    ->imageEditor(),
                TextInput::make('alt')
                    ->label('圖片描述'),
                Toggle::make('status')
                    ->onColor('success')
                    ->offColor('danger'),
            ]);

    }

    public static function table(Table $table): Table

    {
        return $table
//            ->modifyQueryUsing(fn (Builder $query) => $query->where('image_path', true))
            ->reorderable('orderby')
            ->defaultSort('orderby', 'asc')
            ->columns([

                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('image_path')
                        ->height('50%')
                        ->width('100%'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\ToggleColumn::make('status')
                            ->label('顯示'),
                    ]),
                ])->space(3),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated([
                18,
                36,
                72,
                'all',
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ReplicateAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->selectCurrentPageOnly()
            ;
    }

    public static function getPages(): array
    {

        return [
            'index' => Pages\ManageIndexCarousels::route('/'),
        ];
    }
}
