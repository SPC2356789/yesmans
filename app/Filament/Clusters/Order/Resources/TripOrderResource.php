<?php

namespace App\Filament\Clusters\Order\Resources;

use App\Filament\Clusters\Order;
use App\Filament\Clusters\Order\Resources\TripOrderResource\Pages;
use App\Filament\Clusters\Order\Resources\TripOrderResource\RelationManagers;
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
                    ->searchable(),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_last_five')
                    ->label('帳戶後五碼')
                    ->searchable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('狀態')
                    ->sortable()
                    ->icon(fn ($state) => config("order_statuses.$state.icon") ?? 'heroicon-o-question-mark-circle')
                    ->color(fn ($state) => config("order_statuses.$state.color") ?? 'gray')
                    ->tooltip(fn ($state) => config("order_statuses.$state.text") ?? '未知狀態'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            //
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
