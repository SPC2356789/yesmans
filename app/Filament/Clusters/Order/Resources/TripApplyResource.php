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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Helper\ShortCrypt;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class TripApplyResource extends Resource
{
    protected static ?string $model = TripApply::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Order::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('id')
                    ->label('序號')
                    ->content(function ($record) {
                        return $record ? $record->id : 'N/A';
                    })
                ,
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('名字')
                    ->maxLength(255),
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->label('訂單編號')
                    ->maxLength(255),
                Forms\Components\Select::make('gender')
                    ->label('性別')
                    ->required()
                    ->options([
                        '男' => '男',
                        '女' => '女',
                    ]),
                Forms\Components\DatePicker::make('birthday')
                    ->label('生日')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('電子郵件')
                    ->email()
                    ->formatStateUsing(fn($state) => $state ? ShortCrypt::decrypt($state) : $state)
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('電話')
                    ->tel()
                    ->formatStateUsing(fn($state) => $state ? ShortCrypt::decrypt($state) : $state)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('country')
                    ->label('國家')
                    ->required()
                    ->options(function () {
                        // 讀取 JSON 檔案
                        $jsonPath = public_path('lib/trip_country.json');
                        if (!File::exists($jsonPath)) {
                            return [];
                        }

                        $countries = json_decode(File::get($jsonPath), true);
                        $options = [];

                        foreach ($countries as $country) {
                            // 提取中文名稱
                            $labelText = '';
                            if (!empty($country['translations']['zho']['common'])) {
                                $labelText = $country['translations']['zho']['common'];
                            } elseif (!empty($country['name']['nativeName']['zho']['common'])) {
                                $labelText = $country['name']['nativeName']['zho']['common'];
                            } else {
                                $labelText = $country['name']['common']; // 回退到英文名稱
                            }

                            // 生成 value: 中文名稱(cca3)
                            $value = "{$labelText}({$country['cca3']})";

                            // 生成 label: HTML 結構
                            $label = <<<HTML
<div class="flex flex-row items-center w-full ">
    <img class="w-6 mx-3" src="{$country['flags']['png']}" alt="{$labelText}" loading="lazy" />
    <span>{$value}<br>{$country['name']['common']}</span>
</div>
HTML;

                            $options[$value] = $label;
                        }

                        return $options;
                    })
                    ->allowHtml()
                    ->searchable() // 啟用搜尋功能

                ,
                Forms\Components\TextInput::make('id_card')
                    ->label('身分證/居留證')
                    ->required()
                    ->formatStateUsing(fn($state) => $state ? ShortCrypt::decrypt($state) : $state)
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('地址')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('PassPort')
                    ->label('護照')
                    ->formatStateUsing(fn($state) => $state ? ShortCrypt::decrypt($state) : $state)
                    ->maxLength(255),
                Forms\Components\Select::make('diet')
                    ->label('飲食偏好') // 可選：設置更明確的標籤
                    ->required()
                    ->options([
                        '葷食' => '葷食',
                        '素食' => '素食',
                    ]),
                Forms\Components\TextInput::make('experience')
                    ->label('經驗')
                    ->maxLength(255),
                Forms\Components\TextInput::make('disease')
                    ->label('疾病')
                    ->maxLength(255),
                Forms\Components\TextInput::make('LINE')
                    ->maxLength(255),
                Forms\Components\TextInput::make('IG')
                    ->maxLength(255),
                Forms\Components\TextInput::make('emContact')
                    ->required()
                    ->label('緊急連絡人')
                    ->maxLength(255),
                Forms\Components\TextInput::make('emContactPh')
                    ->label('緊急連絡人電話')
                    ->required()
                    ->formatStateUsing(fn($state) => $state ? ShortCrypt::decrypt($state) : $state)
                    ->maxLength(255),
                Forms\Components\FileUpload::make('passport_pic')
                    ->label('護照照片上傳')
                    ->image()
                    ->directory(fn($get) => 'passport') // 📂 設定儲存目錄
                    ->disk('private')
                    ->visibility('private')
                    ->imageEditor()
                    ->getUploadedFileNameForStorageUsing(function (UploadedFile $file, $get): string {
                        return $get('PassPort') ?? $get('id_card'); // 只使用護照號碼作為檔名
                    })
                    ->dehydrated(true), // 確保狀態被保存
                Forms\Components\ViewField::make('passport_')
                    ->label('護照照片預覽')
                    ->view('components.passport-pic-preview')
                    ->dehydrated(false), // 確保狀態被保存
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc') // 設置預設排序：created_at 降序
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('姓名')
                    ->searchable()
                ,
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
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('country')
                    ->label('國家')
                    ->options(function () {
                        $jsonPath = public_path('lib/trip_country.json');
                        if (!File::exists($jsonPath)) {
                            return [];
                        }

                        $countries = json_decode(File::get($jsonPath), true);
                        return collect($countries)->mapWithKeys(function ($country) {
                            $labelText = $country['translations']['zho']['common'] ??
                                $country['name']['nativeName']['zho']['common'] ??
                                $country['name']['common'];
                            $value = "{$labelText}({$country['cca3']})";
                            $englishName = $country['name']['common'];


                            return [$value => $value];
                        })->all();
                    })
                    ->searchable()
                    ->placeholder('請選擇國家')
                  ,

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
            ->selectCurrentPageOnly();
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
