<?php

namespace App\Filament\Clusters\Order\Resources;

use App\Filament\Clusters\Order;
use App\Filament\Clusters\Order\Resources\TripOrderResource\Pages;
use App\Filament\Clusters\Order\Resources\TripOrderResource\RelationManagers;
use App\Models\TripApply;
use App\Models\TripOrder;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Http\Controllers\Controller;
use Filament\Tables\Columns\Layout\Panel;

use Filament\Tables\Columns\Layout\Stack;

class TripOrderResource extends Resource
{
    protected static ?string $model = TripOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Order::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('trip_uuid')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('applies')
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
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
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
                Tables\Columns\TextColumn::make('trip_uuid')
                    ->label('行程編號')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('trip_times.trip.title')
                    ->label('行程資訊')
                    ->searchable()
                    ->formatStateUsing(fn($record) => ($trip = $record->trip_times->first()?->trip)
                        ? "{$trip->title} - {$trip->subtitle}"
                        : ''
                    ),
                Tables\Columns\TextColumn::make('trip_times.date_start')
                    ->label('行程時間')
                    ->searchable()
                    ->formatStateUsing(fn($record) => ($time = $record->trip_times->first())
                        ? Carbon::parse($time->date_start)->isoFormat('Y-M-D (dd)') .
                        ($time->date_start !== $time->date_end
                            ? ' ~ ' . Carbon::parse($time->date_end)->isoFormat('Y-M-D (dd)')
                            : ' (單攻)')
                        : ''
                    ),
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
//                    ->formatStateUsing(function ($state) {
//                        // 如果 $state 是 JSON 字符串，解碼為陣列
//                        $state = is_string($state) ? json_decode($state, true) : $state;
//                        if (!is_array($state) || empty($state)) {
//                            return '無團員';
//                        }
//
//                        // 查詢團員名稱
//                        $applies = TripApply::whereIn('id', $state)->pluck('name')->toArray();
//                        $text = implode(', ', $applies) ?: '無團員';
//
//                        // 每 4 字元插入換行符
//                        $formattedText = '';
//                        $length = mb_strlen($text);
//                        for ($i = 0; $i < $length; $i += 4) {
//                            $formattedText .= mb_substr($text, $i, 4) . '<br>';
//                        }
//                        // 移除最後多餘的 <br>
//                        $formattedText = rtrim($formattedText, '<br>');
//
//                        return "<span>{$formattedText}</span>";
//                    })
//                    ->html() // 允許 HTML 渲染

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
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('account_last_five')
                    ->label('帳戶後五碼')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TripAppliesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTripOrders::route('/'),
            'create' => Pages\CreateTripOrder::route('/create'),
            'edit' => Pages\EditTripOrder::route('/{record}/edit'),
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
