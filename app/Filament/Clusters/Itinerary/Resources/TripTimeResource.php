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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

class TripTimeResource extends Resource
{
    protected static ?string $model = TripTime::class;

    protected static ?string $tripMould = Trip::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Itinerary::class;

    public static function form(Form $form): Form
    {


        return $form
            ->schema([
                Select::make('mould_id')
                    ->options(self::$tripMould::getData_form())// 從分類模型中獲取選項
                    ->searchable() // 支持搜索
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
//                        // 當模板選擇後更新名額和金額
                        $template = self::$tripMould::find($state); // 假設你可以根據選擇的模板ID獲取模板資料
//                        dd($template->quota);
                        if ($template) {
                            // 更新名額和金額欄位
                            $set('quota', $template->quota); // 假設模板有名額欄位
                            $set('amount', $template->amount); // 假設模板有金額欄位
                            $set('agreement_content', $template->agreement_content); // 假設模板有金額欄位
                        }
                    }),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('NT$'),

                Flatpickr::make('date_start')
                    ->hidden()
                ,
                Flatpickr::make('date_end')
                    ->hidden()
                ,
                Flatpickr::make('date')
                    ->range()// Use as a Date Range Picker
                    ->required()
                ,
                Forms\Components\TextInput::make('quota')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('agreement_content')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('food')
                    ->required(),

            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
//            ->modifyQueryUsing(fn(Builder $query) => $query->where('date_start', '>', Carbon::today()))
            ->defaultSort('date_start', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('Trip.title')
                    ->label('模板')
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
                    ->getStateUsing(fn($record) => // 檢查 date_start 和 date_end 是否不同
                    ($record->date_start !== $record->date_end)
                        ? $record->date_start . ' ~ ' . $record->date_end  // 如果不同，顯示範圍
                        : $record->date_start . ' ( 單攻 )' // 如果相同，僅顯示 date_start
                    )
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quota')
                    ->label('名額')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('food')
                    ->boolean(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('發布'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
//                Filter::make('before_date')
//                    ->label('檢閱已經結束的行程') // 過濾器標籤
//                    ->toggle() // 讓這個篩選器變成開關（可開/關）
//                    ->query(function (Builder $query): Builder {
//                        return $query->whereDate('date_start',   '<' , Carbon::today());
//                    }),
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
                        return $query
                            ->when(
                                $data['date_start'],
                                fn(Builder $query, $range): Builder => $query
                                    ->tap(function () use (&$range) { // 使用 & 確保 $range 被修改
                                        if (!is_array($range)) { // 如果 $range 不是陣列
                                            $range = explode(' to ', $range); // 將範圍字符串分割成陣列
                                        }
                                    }) ->when(
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
                Tables\Actions\EditAction::make()//date
                ->mutateRecordDataUsing(function (array $data): array {
                    $data['date'] = array($data['date_start'] ?? null, $data['date_end'] ?? $data['date_start']);

                    return $data;
                })
                    ->mutateFormDataUsing(function (array $data): array {
//                        dd($data);
                        $data['date_start'] = $data['date'][0];
                        $data['date_end'] = $data['date'][1];
                        unset($data['date']);
                        return $data;
                    })
                ,
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTripTimes::route('/'),
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
