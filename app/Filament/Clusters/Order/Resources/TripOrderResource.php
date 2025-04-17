<?php

namespace App\Filament\Clusters\Order\Resources;

use Illuminate\Support\HtmlString;
use App\Filament\Clusters\Order;
use App\Filament\Clusters\Order\Resources\TripOrderResource\Pages;
use App\Filament\Clusters\Order\Resources\TripOrderResource\RelationManagers;
use App\Models\Trip;
use App\Models\TripApply;
use App\Models\TripOrder;
use App\Models\TripTime;
use Carbon\Carbon;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Http\Controllers\Controller;
use Filament\Tables\Columns\Layout\Panel;

use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;

class TripOrderResource extends Resource
{
    protected static ?string $model = TripOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Order::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(function ($record) {

                $original_amount = TripTime::where('uuid', $record->trip_time_uuid ?? 0)->first()->fake_amount ?? 0; // (原價)
                $original_total = $original_amount ? $original_amount * $record->appliesCount : null;//總額(原價) (若原價沒有則不計算總額)


                return [
                    Forms\Components\TextInput::make('order_number')
                        ->label('訂單編號')
                        ->required()
//                    ->disabled() // 禁止修改
                        ->maxLength(255),
                    Forms\Components\TextInput::make('entry_number')
                        ->label('入山編號')
                    ,
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
                                    if ($livewire->record && $currentUuid = $livewire->record->times->uuid) {
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
                    Forms\Components\Select::make('status')
                        ->label('訂單狀態')
                        ->options(function (?string $state, $get, $set) use ($original_amount) {
                            // 從 config('order_statuses') 獲取所有狀態
                            $statuses = config('order_statuses');

                            // 定義分組結構
                            $options = [
                                '報名階段' => collect($statuses)
                                    ->only(['10'])
                                    ->mapWithKeys(function ($item, $key) {
                                        return [$key => "{$item['text']} : {$item['note']}"];
                                    })
                                    ->all(),
                                '付款處理' => collect($statuses)
                                    ->only(['11', '12', '41', $original_amount ? '42' : ''])
                                    ->mapWithKeys(function ($item, $key) {
                                        return [$key => "{$item['text']} : {$item['note']}"];
                                    })
                                    ->all(),
                                '問題處理' => collect($statuses)
                                    ->only(['14', '15'])
                                    ->mapWithKeys(function ($item, $key) {
                                        return [$key => "{$item['text']} : {$item['note']}"];
                                    })
                                    ->all(),
                                '取消處理' => collect($statuses)
                                    ->only(['91', '92', '93', '94'])
                                    ->mapWithKeys(function ($item, $key) {
                                        return [$key => "{$item['text']} : {$item['note']}"];
                                    })
                                    ->all(),
                                '終止狀態' => collect($statuses)
                                    ->only(['98', '1', $original_amount ? '99' : ''])
                                    ->mapWithKeys(function ($item, $key) {
                                        return [$key => "{$item['text']} : {$item['note']}"];
                                    })
                                    ->all(),
                            ];

                            return $options;
                        })
                        ->default(10)
                        ->required()
                        ->live()
                        ->reactive() // 確保即時更新
                        ->afterStateUpdated(function (?string $state, callable $set, $get) use ($original_total, $original_amount) {

                            $paid = $get('paid_amount');
                            $today = date('Y-m-d'); // 今天的日期
                            $setOP = "\n-";
                            switch ($state) {
                                case '41': //已匯訂
                                    $set('paid_amount', $get('total_amount'));
                                    break;
                                case '42': //已完款
                                    if (is_numeric($paid)) {

                                        $additionalAmount = $original_total ? $original_total - $paid : $paid; // 這次要增加的金額

                                        $history = "\n{$today}\n(+{$additionalAmount})";
                                        $set('paid_amount', $paid . $history . $setOP);
                                    }
                                    break;
                                case '98': //已退訂
                                    if (is_numeric($paid)) {
                                        $history = "\n{$today}\n(退訂 {$paid})";
                                        $set('paid_amount', $paid . $history . $setOP);
                                    }
                                    break;
                                case '99': //已退全款
                                    $total = $original_total ?? $paid;
                                    $history = "\n{$today}\n(退全款 {$total})";
                                    $set('paid_amount', $paid . $history . $setOP);
                                    break;
                                default:
                                    break;
                            }
                        })
                        ->visible(fn($record) => $record !== null),
                    Forms\Components\TextInput::make('account_last_five')
                        ->label('匯款後五碼')
                        ->maxLength(10),

                    Forms\Components\Textarea::make('paid_amount')
                        ->label('已付款金額')
                        ->autosize(), // 啟用自動調整高度
                    Forms\Components\Placeholder::make('total_amount')
                        ->label('')
                        ->hint(fn($get) => '訂單總金額 ' . $get('total_amount'))
                    ,

                    Forms\Components\Placeholder::make('lave_amount')
                        ->label('')
                        ->hint(function () use ($original_total, $original_amount) {
                            return new HtmlString("原價<br> 訂單總額/每位金額 :  {$original_total}/{$original_amount}");
                        })
                        ->hintColor(fn($get) => 'warning')

                    ,
                    Forms\Components\Placeholder::make('lave_original')
                        ->label('')
                        ->hint(fn($get, $record) => new HtmlString(
                            match ($get('status')) {
                                98 => "已退訂",
                                99 => "已退全款",
                                42 => "已完款<br>全部金額已支付",
                                default => ($get('lave_amount') > 0 ? "未付款金額 {$get('lave_amount')}" : "已全部付款"),
                            }

                        ))
                        ->hintColor(fn($get) => ($get('lave_amount') > 0 ? 'danger' : 'success')),
                ];
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // 設置預設排序：created_at 降序
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('訂單編號')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('copied')
                    ->copyMessageDuration(1500)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(function ($state) {
                        $parts = explode('_', $state);
                        return implode('<br>', $parts);
                    })
                    ->html() // 允許 HTML 渲染
                ,

                Tables\Columns\TextColumn::make('times.title')
                    ->label('行程資訊')
                    ->searchable(query: function ($query, $search) {
                        return $query->orWhereHas('times.trip', function ($query) use ($search) {
                            $query->where('title', 'like', "%{$search}%")
                                ->orWhere('subtitle', 'like', "%{$search}%");
                        });
                    })
                    ->getStateUsing(function ($record) {
                        $trip = $record->times()->first()?->trip;
                        return $trip ? "{$trip->title} <br> {$trip->subtitle}" : '';
                    })
                    ->html() // 啟用 HTML 渲染
                ,
                Tables\Columns\TextColumn::make('times.date_start')
                    ->label('行程時間')
                    ->sortable()
                    ->searchable()
                    ->lineClamp(5)
                    ->wrap()
                    ->extraAttributes(['style' => 'width: 140px;'])
                    ->getStateUsing(fn($record) => ($time = $record->times()->first())
                        ? Carbon::parse($time->date_start)->isoFormat('Y-M-D (dd)') .
                        ($time->date_start !== $time->date_end
                            ? ' ~ ' . Carbon::parse($time->date_end)->isoFormat('Y-M-D (dd)')
                            : ' (單攻)')
                        : ''
                    ),
                Tables\Columns\TextColumn::make('applies.name')
                    ->label('團員')
                    ->searchable()
                    ->html() // 啟用 HTML 渲染
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                ,

                Tables\Columns\TextColumn::make('amount')
                    ->label('每人金額')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('total_amount') // 新增總金額欄位
                ->label('總金額')
                ,
                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('已付金額')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextInputColumn::make('entry_number')
                    ->label('入山編號')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
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

            ])
            ->recordUrl(null) // 禁用整行點擊導航

            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('status')
                    ->label('訂單狀態')
                    ->options(function () {
                        // 動態生成下拉選單選項
                        return collect(config('order_statuses'))->mapWithKeys(function ($status, $key) {
                            return [$key => $status['text'] . "\n" . $status['note']];
                        })->all();
                    })
                    ->attribute('status'),
                Filter::make('date')
                    ->form([
                        Flatpickr::make('date_start')
                            ->label('出團日期')
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
            ->actions(
                [
                    Tables\Actions\EditAction::make(),
                ],
                position: ActionsPosition::BeforeColumns
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->selectCurrentPageOnly();
    }

// 明確禁用創建功能
    public static function canCreate(): bool
    {
        return false;
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
