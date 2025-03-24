<?php

namespace App\Filament\Clusters\Itinerary\Resources;

use App\Filament\Clusters\Itinerary;
use App\Filament\Clusters\Itinerary\Resources\TripTimeResource\Pages;
use App\Filament\Clusters\Itinerary\Resources\TripTimeResource\RelationManagers;
use App\Models\Trip;
use App\Models\TripTime;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
class TripTimeResource extends Resource
{
    protected static ?string $model = TripTime::class;

    protected static ?string $tripMould = Trip::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Itinerary::class;
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {


        return $form
            ->schema([
                Forms\Components\Select::make('mould_id')
                    ->label('選擇行程')
                    ->options(Trip::getData_form())// 從分類模型中獲取選項
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
//                        // 當模板選擇後更新名額和金額
                        $template = self::$tripMould::find($state); // 假設你可以根據選擇的模板ID獲取模板資料

                        if ($template) {
                            // 更新名額和金額欄位
                            $set('quota', $template->quota); // 假設模板有名額欄位
                            $set('amount', $template->amount); // 假設模板有金額欄位
                            $set('agreement_content', $template->agreement_content); // 假設模板有金額欄位
                            $set('hintMonth', $template->hintMonth); // 假設模板有金額欄位
                            $set('passport_enabled', $template->passport_enabled); // 假設模板有護照開啟欄位
                        }
                    })
                ,
                Forms\Components\TextInput::make('fake_amount')
                    ->label('原價')
                    ->prefix('NT$'),
                Forms\Components\TextInput::make('amount')
                    ->label('費用')
                    ->required()
                    ->prefix('NT$'),

                Forms\Components\DatePicker::make('date_start')
                    ->hidden()
                ,
                Forms\Components\DatePicker::make('date_end')
                    ->hidden()
                ,
               Flatpickr::make('date_range')
                    ->label('開團日期')
                    ->range()
                ,
                Forms\Components\TextInput::make('quota')
                    ->label('名額')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('hintMonth')
                    ->label('提示月份')
                    ->options(array_combine(range(0, 12), range(0, 12))) // 自動產生 0~12 的選項
                ,
                Forms\Components\Textarea::make('agreement_content')
                    ->label('同意書內容')
                    ->rows(5) // 設定高度為 5 行
                ,
                Forms\Components\Placeholder::make('applied_count')
                    ->label('已報名人數')
                    ->content(function ($record) {
                        // $record 是當前的 TripTimes 模型實例
                        if (!$record) {
                            return 0;
                        }

                        // 獲取所有相關的 TripOrder
                        $orders = $record->Orders;

                        // 計算所有 TripOrder 的 TripApply 總數
                        $totalApplies = $orders->reduce(function ($carry, $order) {
                            return $carry + $order->applies()->count();
                        }, 0);

                        return $totalApplies;
                    }),
                Forms\Components\Toggle::make('food')
                    ->label('有無搭伙')
                    ->required(),
                Forms\Components\Toggle::make('passport_enabled')
                    ->label('護照號碼是否開啟')
                    ->required(),

            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
//            ->modifyQueryUsing(fn(Builder $query) => $query->where('date_start', '>', Carbon::today()))
            ->defaultSort('date_start', 'asc')
            ->columns([
                Tables\Columns\IconColumn::make('hintMonth')
                    ->label('-')
                    ->getStateUsing(function ($record) {
                        // 解析數據
                        $startDate = Carbon::parse($record->date_start); // 開始日期
                        $hintMonth = (int)$record->hintMonth; // 提示月份
                        $quota = (int)$record->quota; // 總名額
                        $appliedCount = (int)$record->applied_count; // 已報名人數
                        $is_published = (boolean)$record->is_published; // 已報名人數

                        // 計算 `hintMonth` 月內的截止日期
                        $cutoffDate = $startDate->copy()->subMonths($hintMonth);
                        $showIcon = $hintMonth && ($quota - $appliedCount >= 1) && now()->between($cutoffDate, $startDate) && $is_published;

                        // **重要：這裡不應該直接給 `$record->show_icon`，因為資料庫不會存這個欄位**
                        return $showIcon ? true : null; // 若條件不符，不返回 icon
//                        return $cutoffDate;
                    })
                    ->boolean()
                    ->trueColor('danger')
                    ->trueIcon('heroicon-o-exclamation-triangle')
//                    ->falseIcon('heroicon-o-x-mark')
                    ->sortable(query: function ($query, $direction) {
                        return $query->orderByRaw("
 (hintMonth > 0
AND CAST(quota AS SIGNED) - CAST(applied_count AS SIGNED) >= 1
AND NOW() BETWEEN DATE_SUB(date_start, INTERVAL hintMonth MONTH) AND date_start)
 $direction
    ")->orderBy('date_start', $direction === 'asc' ? 'desc' : 'asc');
                    }),
                Tables\Columns\TextColumn::make('Trip.title')
                    ->label('模板')
                    ->searchable([
                        'trips.title',          // 搜尋 Trip 表的 title
                        'trips.subtitle',       // 搜尋 Trip 表的 subtitle
                    ])
                    ->getStateUsing(function ($record) {
                        return $record->Trip->title . '-' . $record->Trip->subtitle; // 合併 name 和 title
                    }),
//                Tables\Columns\TextColumn::make('mould_id')
//                    ->numeric()
//                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('費用')
                    ->money('TWD') // 顯示台幣格式
                    ->sortable(),

                Tables\Columns\TextColumn::make('date_start')
                    ->label('日期')
                    ->getStateUsing(fn($record) => Carbon::parse($record->date_start)->isoFormat('Y-M-D (dd)') .
                        ($record->date_start !== $record->date_end
                            ? ' ~ ' . Carbon::parse($record->date_end)->isoFormat('Y-M-D (dd)')
                            : ' (單攻)')
                    )
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quota')
                    ->label('名額')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('applied_count')
                    ->label('已報名')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('available_spots')
                    ->label('剩餘')
                    ->getStateUsing(fn($record) => (int)$record->quota - (int)$record->applied_count) // 允許負數
                    ->sortable(query: function ($query, $direction) {
                        return $query->orderByRaw("CAST(quota AS SIGNED) - CAST(applied_count AS SIGNED) $direction");
                    }),

                Tables\Columns\IconColumn::make('food')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('發布'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Filter::make('date')
                    ->form([
                        Flatpickr::make('date_start')
                            ->default(function () {
                                // 設定預設範圍為今天起算，一年後
                                $today = \Carbon\Carbon::today()->toDateString(); // 當前日期
                                $oneYearLater = \Carbon\Carbon::today()->addYear()->toDateString(); // 一年後的日期
                                return $today . ' to ' . $oneYearLater; // 開始日期為今天，結束日期為一年後
                            })
                            ->range(),

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_start'],
                                fn(Builder $query, $range): Builder => $query
                                    ->tap(function () use (&$range) { // 使用 & 確保 $range 被修改
                                        if (!is_array($range)) { // 如果 $range 不是陣列
                                            $range = explode(' to ', $range); // 將範圍字符串分割成陣列
                                        }
                                    })->when(
                                        isset($range[0]) && $range[0], // 檢查開始日期是否存在且有效
                                        fn(Builder $query) => $query->whereDate('date_start', '>=', $range[0]),
                                    )
                                    ->when(
                                        isset($range[1]) && $range[1], // 檢查結束日期是否存在且有效
                                        fn(Builder $query) => $query->whereDate('date_start', '<=', $range[1]),
                                    )
                            );
                    })
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


    public static function getRelations(): array
    {
        return [
            RelationManagers\TripOrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTripTime::route('/'),
            'create' => Pages\CreateTripTime::route('/create'),
            'edit' => Pages\EditTripTime::route('/{record}/edit'),
        ];
    }

    protected static ?string $title = '立即開團';

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
