<?php

namespace App\Filament\Clusters\About\Resources;

use App\Filament\Clusters\About;
use App\Filament\Clusters\About\Resources\AboutMemberResource\Pages;

//use App\Filament\Clusters\About\Resources\AboutMemberResource\RelationManagers;
use App\Models\AboutMember;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;

class AboutMemberResource extends Resource
{
    protected static ?string $model = AboutMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = About::class;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Split::make([
                    Section::make([
                        Forms\Components\Toggle::make('status')
                            ->label('開啟')
                            ->default(1)
                            ->onColor('success')
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(10),
                        Forms\Components\TextInput::make('orderby')
                            ->default(0)
                            ->hidden(),
                    ]),
                    Section::make([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('頭貼上傳')
                            ->image()
                            ->directory('About/Member') // 指定儲存的目錄
                            ->storeFileNamesIn('original_image_names')
                            ->imageEditor(),
                    ]),
                ]),
                Split::make([
                    Section::make([
                        TinyEditor::make('introduce')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsVisibility('public')
                            ->fileAttachmentsDirectory('uploads')
                            ->profile('aal')
                            ->ltr() // Set RTL or use->direction('auto|rtl|ltr')
                            ->columnSpan('full')
                            ->label('關於我們內容')
                            ->required(),
                    ]),
                ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('orderby')
            ->defaultSort('orderby', 'asc')
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('image_path')
                        ->circular()
                        ->height('50%')
                        ->width('100%'),
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('name')
                            ->label('名稱')
                            ->searchable(),
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
                //
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
            ->selectCurrentPageOnly();
    }

    protected static ?string $title = '成員列表';

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
            'index' => Pages\ManageAboutMembers::route('/'),
        ];
    }
}
