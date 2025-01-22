<?php

namespace App\Filament\Clusters\Itinerary\Resources;

use App\Filament\Clusters\Itinerary;
use App\Filament\Clusters\Itinerary\Resources\ItrySpanResource\Pages;
use App\Filament\Clusters\Itinerary\Resources\ItrySpanResource\RelationManagers;
use App\Models\Categories;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ItrySpanResource extends Resource
{
    protected static ?string $model = Categories::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Itinerary::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('行程標籤'),
                TextInput::make('slug')
                    ->label('標籤代號')
                    ->required()
                    ->rule(
                        Rule::unique('categories', 'slug')
                            ->where('area', 2)          // `area = 1`
                            ->where('type', 2)          // `type = 1`
                            ->whereNull('deleted_at')   // 排除已刪除的
                            ->ignore($form->getRecord() ? $form->getRecord()->id : null) // 排除當前記錄的 id
                    )
                    ->helperText('分類代號在資料庫中必須唯一')
                    ->validationMessages([
                        'unique' => '此 :attribute 的值，已經被使用了',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('orderby')
            ->defaultSort('orderby', 'asc')
            ->modifyQueryUsing(fn(Builder $query) => $query->where('area', 2)->where('type', 2)) //area 文章1 行程2 type 分類1 標籤2
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('名稱'),
                TextColumn::make('slug')
                    ->searchable()
                    ->label('標籤代號'),
                ToggleColumn::make('status')
                    ->label('顯示'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ReplicateAction::make()
                    ->action(function ($record) {
                        $record->slug = $record->slug . '-' . substr(Str::uuid()->toString(), 0, 8); // 在 slug 後面加上 '-copy' 後綴，確保唯一性
                        $record->replicate()->save();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    protected static ?string $title = '行程標籤';

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
            'index' => Pages\ManageItrySpans::route('/'),
        ];
    }
}
