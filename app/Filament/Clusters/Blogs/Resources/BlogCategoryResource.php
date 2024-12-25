<?php

namespace App\Filament\Clusters\Blogs\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Clusters\Blogs;
use App\Filament\Clusters\Blogs\Resources\BlogCategoryResource\Pages;
use App\Filament\Clusters\Blogs\Resources\BlogCategoryResource\RelationManagers;
use App\Models\BlogItem;
use App\Models\Categories;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use mysql_xdevapi\TableSelect;

class BlogCategoryResource extends Resource
{

    protected static ?string $model = Categories::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Blogs::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('文章分類'),
                TextInput::make('slug')
                    ->label('分類代號')
                    ->required()
                    ->rule(
                        Rule::unique('categories', 'slug')
                            ->where('type', 1)          // `type = 1`
                            ->where('area', 1)          // `area = 1`
                            ->whereNull('deleted_at')   // 排除已刪除的
                            ->ignore($form->getRecord() ? $form->getRecord()->id : null) // 排除當前記錄的 id
                    )
                    ->helperText('分類代號在資料庫中必須唯一')
                    ->validationMessages([
                        'unique' => '此 :attribute 的值，已經被使用了',
                    ]),
                TextInput::make('seo_title')
                    ->label('seo主題')
                ,
                TextInput::make('seo_description')
                    ->label('seo介紹')
                ,
                Select::make('seo_image')
                    ->label('設定SEO代表圖(取首圖)')
                    ->options(BlogItem::MapData($form->getRecord() ? $form->getRecord()->id : null))// 從分類模型中獲取選項
                    ->searchable() // 支持搜索
                ,
            ]);


    }


    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('orderby')
            ->defaultSort('orderby', 'asc')
            ->modifyQueryUsing(fn(Builder $query) => $query->where('area', 1)->where('type', 1)) //area 文章1 行程2 type 分類1 標籤2
            ->columns([
                Tables\Columns\textColumn::make('name')
                    ->label('名稱'),
                Tables\Columns\textColumn::make('slug')
                    ->label('分類代號'),
                Tables\Columns\ToggleColumn::make('status')
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

    protected static ?string $title = '文章分類';

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
            'index' => Pages\ManageBlogCategories::route('/'),
        ];
    }
}
