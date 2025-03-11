<?php

namespace App\Filament\Clusters\Itinerary\Resources\TripTimeResource\RelationManagers;

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
                                                ->label('姓名')
                                                ->suffixAction(
                                                    Forms\Components\Actions\Action::make('copy')
                                                        ->icon('heroicon-o-clipboard')
                                                        ->action(fn ($state) => 'navigator.clipboard.writeText("' . $state . '")')
                                                        ->extraAttributes(['title' => '複製到剪貼簿'])
                                                )
                                            ,

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
