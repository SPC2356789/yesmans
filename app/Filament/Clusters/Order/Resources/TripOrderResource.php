<?php

namespace App\Filament\Clusters\Order\Resources;

use App\Filament\Clusters\Order;
use App\Filament\Clusters\Order\Resources\TripOrderResource\Pages;
use App\Filament\Clusters\Order\Resources\TripOrderResource\RelationManagers;
use App\Models\Trip;
use App\Models\TripApply;
use App\Models\TripOrder;
use Carbon\Carbon;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
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
                    ->label('訂單編號')
                    ->required()
//                    ->disabled() // 禁止修改
                    ->maxLength(255),
                Forms\Components\Select::make('selected_times')
//                    ->required()
                    ->label('行程時間')
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->date_start} to {$record->date_end}")
                    ->relationship(
                        name: 'times',
                        modifyQueryUsing: function ($query, callable $get, $livewire) {
                            $orderNumber = $get('order_number');
                            if ($orderNumber) {
                                // 篩選與指定 trip 相關的行程時間
                                $query->whereHas('trip', fn($q) => $q->where('slug', explode('_', $orderNumber)[1] ?? ''));

                                // 檢查是否為編輯模式並獲取當前選中的行程時間 uuid
                                if ($livewire->record && $currentUuid = $livewire->record->times->first()?->uuid) {
                                    $query->where(function ($q) use ($currentUuid) {
                                        $q->whereDate('date_start', '>=', Carbon::today()) // 正常情況下篩選今天或之後
                                        ->orWhere('uuid', $currentUuid); // 包含當前選中的 uuid，即使是過去的
                                    });
                                } else {
                                    // 新增模式，只顯示今天或之後的行程時間
                                    $query->whereDate('date_start', '>=', Carbon::today());
                                }

                                $query->orderBy('date_start', 'ASC');
                            } else {
                                // 如果 order_number 為空，返回空查詢
                                $query->whereRaw('1 = 0');
                            }
                            return $query;
                        }
                    )
                    ->dehydrated(false),
                Forms\Components\TextInput::make('amount')
                    ->label('每位成員金額')
                    ->required()
                    ->numeric()
                    ->reactive() // 使欄位反應，當值改變時觸發更新
                ,

                Forms\Components\TextInput::make('paid_amount')
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_last_five')
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('訂單狀態')
                    ->options(
                        collect(config('order_statuses'))
                            ->mapWithKeys(function ($item, $key) {
                                return [$key => $item['text'] . $item['note']];
                            })
                            ->all()
                    )
                    ->default(10)
                    ->required()
                ,
                Forms\Components\Placeholder::make('total_amount')
                    ->label('')
                    ->hint(fn ($get) => '訂單總金額'.$get('total_amount') )
                   ,
                Forms\Components\Placeholder::make('lave_amount')
                    ->label('')

                    ->hint(fn ($get, $record) => $get('lave_amount') > 0 ? '未付款金額 '.$record->lave_amount : '已全部付款')
                    ->hintColor(fn ($get) => $get('lave_amount') > 0 ? 'danger' : 'success')
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
                Tables\Columns\TextColumn::make('times.title')
                    ->label('行程資訊')
                    ->getStateUsing(fn($record) => ($trip = $record->times()->first()?->trip)
                        ? "{$trip->title} - {$trip->subtitle}"
                        : ''
                    )
                ,
                Tables\Columns\TextColumn::make('times.date_start')
                    ->label('行程時間')
                    ->searchable()
                    ->formatStateUsing(fn($record) => ($time = $record->times()->first())
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
                Filter::make('date')
                    ->form([
                        Flatpickr::make('date_start')
                            ->default(function () {
                                // 設定預設範圍為今天起算，一年後
                                $today = \Carbon\Carbon::today()->toDateString(); // 當前日期
                                $oneYearLater = \Carbon\Carbon::today()->addYear()->toDateString(); // 一年後的日期
                                return [$today, $oneYearLater]; // 開始日期為今天，結束日期為一年後
                            })
                            ->range(),

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $result = $query->when(
                            $data['date_start'],
                            function (Builder $query, $range) {
                                if (!is_array($range)) {
                                    $range = explode(' to ', $range);
                                }
                                $query->whereHas('times', function (Builder $subQuery) use ($range) {
                                    $subQuery
                                        ->when(
                                            isset($range[0]) && $range[0],
                                            fn(Builder $subQuery) => $subQuery->whereDate('date_start', '>=', $range[0])
                                        )
                                        ->when(
                                            isset($range[1]) && $range[1],
                                            fn(Builder $subQuery) => $subQuery->whereDate('date_start', '<=', $range[1])
                                        );
                                });
                            }
                        );
                        return $result;
                    })
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

    protected static ?int $navigationSort = 1;
    protected static ?string $title = '訂單';

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
