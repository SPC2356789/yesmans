<?php

namespace App\Filament\Clusters\Blogs\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Clusters\Blogs;
use App\Filament\Clusters\Blogs\Resources\BlogItemResource\Pages;
use App\Filament\Clusters\Blogs\Resources\BlogItemResource\RelationManagers;

use App\Models\BlogItem;
use App\Models\Categories;
use Filament\Forms;
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
                    FileUpload::make('featured_image')
                        ->label('首圖')
                        ->image()
                        ->directory('Blog/Items')
                        ->helperText('只支持jpg與png前端編輯') // 輔助說明文字
                        ->imageEditor(),
                ]),
                Split::make([
                    Section::make([
                        TextInput::make('title')
                            ->label('文章標題') // 自訂欄位標籤
                            ->required() // 必填
                            ->maxLength(255), // 最大長度
                        Select::make('category_id')
                            ->label('文章分類')
//                            ->relationship('Categories', 'name') // 建立與關聯模型的對應
                            ->options(self::$category::getData(1, 1))// 從分類模型中獲取選項
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
            ->columns([
                Tables\Columns\textColumn::make('id')
                    ->label('id'),
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('首圖'),
                Tables\Columns\textColumn::make('title')
                    ->label('文章標題'),
                Tables\Columns\textColumn::make('Categories.name')
                    ->label('文章分類'),
                Tables\Columns\textColumn::make('slug')
                    ->label('文章代號'),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('發布狀態'),
            ])
            ->filters([
                //
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
