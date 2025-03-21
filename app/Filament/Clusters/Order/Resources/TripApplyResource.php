<?php

namespace App\Filament\Clusters\Order\Resources;

use App\Filament\Clusters\Order;
use App\Filament\Clusters\Order\Resources\TripApplyResource\Pages;
use App\Filament\Clusters\Order\Resources\TripApplyResource\RelationManagers;
use App\Models\TripApply;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Helper\ShortCrypt;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Actions\ActionGroup;
class TripApplyResource extends Resource
{
    protected static ?string $model = TripApply::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Order::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gender')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birthday')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->formatStateUsing(fn($state) => $state ? ShortCrypt::decrypt($state) : $state)
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->formatStateUsing(fn($state) => $state ? ShortCrypt::decrypt($state) : $state)
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('id_card')
                    ->required()
                    ->formatStateUsing(fn($state) => $state ? ShortCrypt::decrypt($state) : $state)
                    ->maxLength(255),
                Forms\Components\TextInput::make('PassPort')
                    ->required()
                    ->maxLength(255)
                    ->formatStateUsing(fn($state) => $state ? ShortCrypt::decrypt($state) : $state),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('diet')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('experience')
                    ->maxLength(255),
                Forms\Components\TextInput::make('disease')
                    ->maxLength(255),
                Forms\Components\TextInput::make('LINE')
                    ->maxLength(255),
                Forms\Components\TextInput::make('IG')
                    ->maxLength(255),
                Forms\Components\TextInput::make('emContactPh')
                    ->required()
                    ->formatStateUsing(fn($state) => $state ? ShortCrypt::decrypt($state) : $state)
                    ->maxLength(255),
                Forms\Components\TextInput::make('emContact')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // 設置預設排序：created_at 降序
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('姓名')
                    ->searchable(isIndividual: true)
                ,
                Tables\Columns\TextColumn::make('order_number')
                    ->label('訂單編號')
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('gender')
                    ->label('性別')
                ,
                Tables\Columns\TextColumn::make('birthday')
                    ->label('生日')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->label('電子郵件')
                    ->searchable(query: function ($query, $search) {
                        if ($search) {
                            $encryptedSearch = hash('sha256', $search);
                            $query->where('email_hash', $encryptedSearch);
                        }
                        return $query;
                    }, isIndividual: true)
                    ->toggleable(isToggledHiddenByDefault: true)
                ,   // 顯示時解密
                Tables\Columns\TextColumn::make('phone')
                    ->label('電話')
                    ->searchable(query: function ($query, $search) {
                        if ($search) {
                            $encryptedSearch = hash('sha256', $search);
                            $query->where('phone_hash', $encryptedSearch);
                        }
                        return $query;
                    }, isIndividual: true)
                ,   // 顯示時解密
                Tables\Columns\TextColumn::make('country')
                    ->label('國家')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('id_card')
                    ->label('身份證號')
                    ->searchable(isIndividual: true)
                ,   // 顯示時解密
                Tables\Columns\TextColumn::make('address')
                    ->label('地址')
                    ->toggleable(isToggledHiddenByDefault: true)
                ,   // 顯示時解密
                Tables\Columns\TextColumn::make('PassPort')
                    ->label('護照號碼')
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('LINE')
                    ->label('LINE ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('IG')
                    ->label('Instagram')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('emContactPh')
                    ->label('緊急聯絡電話')
                    ->toggleable(isToggledHiddenByDefault: true)

                ,   // 顯示時解密
                Tables\Columns\TextColumn::make('emContact')
                    ->label('緊急聯絡人')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable()
                ,
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('刪除時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ])->iconButton(),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->selectCurrentPageOnly()

            ;
    }

    protected static ?int $navigationSort = 2;
    protected static ?string $title = '訂單團員';

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
            'index' => Pages\ManageTripApplies::route('/'),
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
