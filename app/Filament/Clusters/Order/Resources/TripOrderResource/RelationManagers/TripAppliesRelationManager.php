<?php

namespace App\Filament\Clusters\Order\Resources\TripOrderResource\RelationManagers;

use App\Helper\ShortCrypt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Http\UploadedFile;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\File;
class TripAppliesRelationManager extends RelationManager
{
    protected static string $relationship = 'applies';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('序號')
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('name')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('姓名')
                ,
                Tables\Columns\TextColumn::make('gender')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('性別')
                    ->formatStateUsing(fn($state) => $state === 'male' ? '男' : ($state === 'female' ? '女' : $state)),

                Tables\Columns\TextColumn::make('birthday')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('生日')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('電子郵件')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('電話')
                ,
                Tables\Columns\TextColumn::make('country')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('國家')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('id_card')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('身份證號')
                    ->searchable()
                ,
                Tables\Columns\TextColumn::make('address')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('地址')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('PassPort')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('護照號碼')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('LINE')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('LINE ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('IG')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('Instagram')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('emContact')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('緊急聯絡人')
                    ->searchable()
                ,
                Tables\Columns\TextColumn::make('emContactPh')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('緊急聯絡電話')
                    ->searchable()
                ,

                Tables\Columns\TextColumn::make('created_at')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                ,
                Tables\Columns\TextColumn::make('updated_at')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('更新時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->copyable()
                    ->copyMessage('copied')
                    ->label('刪除時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordAction(null) // 禁用單行點擊動作
            ->recordUrl(fn() => null) // 明確返回 null 的閉包
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
