<?php

namespace App\Filament\Clusters\Itinerary\Resources\TripTimeResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Forms\Components\select::make('status')//config拉出陣列
                    ->options(
                        collect(config('order_statuses'))
                            ->mapWithKeys(function ($item, $key) {
                                return [$key => $item['text'].$item['note']];
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
            ->recordTitleAttribute('order_number')
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(function () {
                        \Log::info('Edit action triggered'); // 測試是否觸發
                    }),
                Tables\Actions\DeleteAction::make(),
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
