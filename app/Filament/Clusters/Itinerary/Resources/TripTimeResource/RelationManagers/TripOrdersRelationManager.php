<?php

namespace App\Filament\Clusters\Itinerary\Resources\TripTimeResource\RelationManagers;

use App\Helper\ShortCrypt;
use Carbon\Carbon;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\TextEntry;
class TripOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'Orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('trip_uuid')
                    ->required()
                    ->maxLength(255),


                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('paid_amount')
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_last_five')
                    ->maxLength(255),
                Forms\Components\Select::make('status')//config拉出陣列
                ->options(
                    collect(config('order_statuses'))
                        ->mapWithKeys(function ($item, $key) {
                            return [$key => $item['text'] . $item['note']];
                        })
                        ->all()
                )
                    ->default(0)
                    ->required()
                ,
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('訂單編號')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('訂單編號 copied')
                    ->copyMessageDuration(1500)
                ,
                Tables\Columns\TextColumn::make('applies')
                    ->label('團員')
                    ->searchable()
                    ->lineClamp(2)
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('applies', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->formatStateUsing(function ($record) {
                        return $record->applies->pluck('name')->implode(', ') ?: '無團員';
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('每人金額')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('total_amount') // 新增總金額欄位
                ->label('總金額')
                    ->numeric()
                    ->getStateUsing(function ($record) {
                        $applies = json_decode($record->applies); // 假設 applies 是模型屬性
                        $amount = $record->amount;   // 假設 amount 是模型屬性
                        return is_array($applies) ? count($applies) * $amount : $amount;
                    }),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('已付金額')
                    ->searchable()

                ,
                Tables\Columns\TextColumn::make('account_last_five')
                    ->label('帳戶後五碼')
                    ->searchable()

                ,
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn($state) => config("order_statuses.$state.text") ?? '未知狀態')
                    ->description(fn($state): string => config("order_statuses.$state.note"))
                    ->label('狀態')
                    ->sortable()
                    ->icon(fn($state) => config("order_statuses.$state.icon") ?? 'heroicon-o-question-mark-circle')
                    ->color(fn($state) => config("order_statuses.$state.color") ?? 'gray')
                    ->tooltip(fn($state) => config("order_statuses.$state.text") ?? '未知狀態'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
//                Tables\Columns\TextColumn::make('updated_at')
//                    ->label('更新時間')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
//                Tables\Columns\TextColumn::make('deleted_at')
//                    ->label('刪除時間')
//                    ->dateTime()
//                    ->sortable()
//                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(null) // 禁用整行點擊導航
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(function ($record) {
                        return "/yes-admin/order/trip-orders/{$record->id}/edit";
                    })
                    ->openUrlInNewTab()
                ,
                Tables\Actions\ViewAction::make()
                    ->form(function ($record) {
                        return [
                            Forms\Components\TextInput::make('order_number')
                                ->label('訂單編號')
                                ->default($record->order_number)
                                ->disabled(),
                            Forms\Components\Section::make('申請記錄')
                                ->schema([
                                    Forms\Components\Repeater::make('applies')
                                        ->relationship('applies')
                                        ->label('')
                                        ->schema([
                                            Forms\Components\TextInput::make('name')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('gender')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('phone')
                                                ->tel()
                                                ->formatStateUsing(fn ($state) => $state ? ShortCrypt::decrypt($state) : $state)

                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\DatePicker::make('birthday')
                                                ->required(),
                                            Forms\Components\TextInput::make('id_card')
                                                ->required()
                                                ->formatStateUsing(fn ($state) => $state ? ShortCrypt::decrypt($state) : $state)

                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('address')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('emContact')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('emContactPh')
                                                ->required()
                                                ->formatStateUsing(fn ($state) => $state ? ShortCrypt::decrypt($state) : $state)

                                                ->maxLength(255),

                                            Forms\Components\TextInput::make('email')
                                                ->email()
                                                ->formatStateUsing(fn ($state) => $state ? ShortCrypt::decrypt($state) : $state)

                                                ->required()
                                                ->maxLength(255),

                                            Forms\Components\TextInput::make('PassPort')
                                                ->formatStateUsing(fn ($state) => $state ? ShortCrypt::decrypt($state) : $state)
                                                ->maxLength(255),
                                        ])
                                        ->columns(3)
                                        ->disabled(), // 只讀模式
                                ]),
                        ];
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
