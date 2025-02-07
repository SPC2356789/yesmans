<?php

namespace App\Filament\Clusters\Blogs\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Clusters\Blogs;
use App\Filament\Clusters\Blogs\Resources\BlogItemResource\Pages;
use App\Filament\Clusters\Blogs\Resources\BlogItemResource\RelationManagers;
use App\Forms\Components\MediaPicker;
use App\Models\Media;
use Filament\Tables\Filters\SelectFilter;
use App\Models\BlogItem;
use App\Models\Categories;
use Filament\Tables\Filters\Filter;
use Filament\Forms;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogItemResource extends Resource
{
    protected static ?string $model = BlogItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Blogs::class;
    protected static ?string $category = Categories::class;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([

                Split::make([
                    MediaPicker::make('featured_image')
                        ->label('選擇照片')
//                        ->multiple()
                        ->options(Media::getMedia())
                        ->searchable()
//                        ->helperText('超過10張會拖效能')
                        ->columnSpanFull()
                        ->allowHtml(),
                ]),
                Split::make([
                    Section::make([
                        TextInput::make('id')
                            ->readonly(), // 設置為只讀
                        TextInput::make('title')
                            ->label('主標題') // 自訂欄位標籤
                            ->required() // 必填
                            ->maxLength(6), // 最大長度
                        TextInput::make('subtitle')
                            ->label('副標題') // 自訂欄位標籤
                            ->maxLength(8), // 最大長度
                        Select::make('category_id')
                            ->label('文章分類')
                            ->options(self::$category::getData(1, 1)->pluck('name', 'id')->toArray())// 從分類模型中獲取選項
                            ->searchable() // 支持搜索
                            ->required(),
//                        TextInput::make('slug')
//                            ->label('文章代號')
//                            ->required()
//                            ->unique('blog_items', 'slug', ignoreRecord: true) // 唯一性驗證
//                            ->helperText('這將成為文章的 SEO 友好代號'), // 輔助說明文字
                        TextInput::make('slug')
                            ->label('文章代號')
                            ->required()
                            ->rule(
                                Rule::unique('blog_items', 'slug')
                                    ->whereNull('deleted_at')   // 排除已刪除的
                                    ->ignore($form->getRecord() ? $form->getRecord()->id : null) // 排除當前記錄的 id
                            )
                            ->helperText('文章代號在資料庫中必須唯一')
                            ->validationMessages([
                                'unique' => '此 :attribute 的值，已經被使用了',
                            ])
                    ]),
                ]),


                Section::make([
                    Split::make([
                        TextInput::make('seo_title')
                            ->label('SEO_標題')
                            ->required(),
//                        Toggle::make('is_published')//#20241220
//                            ->label('是否發佈')
//                            ->default(false),
                        // 定義日期選擇欄位
                        DateTimePicker::make('published_at')
                            ->label('發佈時間')
                            ->default(now()) // 使用當前的 Carbon 日期物件，包含時間
                            ->format('Y-m-d H:i'), // 設定顯示格式（如果你希望它顯示時間）
                    ]),
                    TextInput::make('seo_description')
                        ->label('seo_description')
                        ->required(),
                    // 定義發佈狀態欄位

                ]),
                Section::make([
                    TinyEditor::make('content')
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsVisibility('public')
                        ->fileAttachmentsDirectory(
                            function ($record) {
                                $base = '/Blog/items/';
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
                        ->label('文章內容')
                        ->required(),
                ]),

//                ]),


            ]);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('orderby')
            ->defaultSort('orderby', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('id')
                    ->toggleable(isToggledHiddenByDefault: true)
                , Tables\Columns\TextColumn::make('orderby')
                    ->sortable()
                    ->label('排序')
                    ->toggleable()
                ,
                Tables\Columns\ImageColumn::make('media.path')
                    ->label('首圖'),
                Tables\Columns\TextColumn::make('title')
                    ->label('主標題')
                    ->limit(10)
                    ->searchable(),
                Tables\Columns\TextColumn::make('Categories.name')
                    ->label('文章分類'),
                Tables\Columns\TextColumn::make('slug')
                    ->label('文章代號')
                    ->searchable()
                    ->toggleable()
                    ->limit(15),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('發布狀態'),
                Tables\Columns\TextColumn::make('published_at')
                    ->sortable()
                    ->toggleable()
                    ->label('發布時間')
                ,
            ])
            ->filters([

                SelectFilter::make('category_id')
                    ->label('文章分類')
                    ->options(self::$category::getData(1, 1)->where('orderby', '>', 0)->pluck('name', 'id')->toArray()),// 根據 'name' 排序)// 從分類模型中獲取選項
                Filter::make('is_published')
                    ->label('發布狀態')
                    ->query(fn(Builder $query): Builder => $query->where('is_published', true))
                    ->toggle()
                ,

            ])
            ->actions([
                Tables\Actions\ReplicateAction::make()
                    ->action(function ($record) {
                        $record->slug = $record->slug . '-' . substr(Str::uuid()->toString(), 0, 8); // 取8碼確保唯一性
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    protected static ?string $title = '文章項目';

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
            'index' => Pages\ListBlogItems::route('/'),
            'create' => Pages\CreateBlogItem::route('/create'),
            'edit' => Pages\EditBlogItem::route('/{record}/edit'),
        ];
    }
}
