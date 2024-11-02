<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsResource\Pages;
use App\Filament\Resources\UsResource\RelationManagers;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use http\Client\Curl\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SolutionForest\FilamentAccessManagement\Support\Utils;

class UsResource extends Resource

{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(strval(__('filament-access-management::filament-access-management.field.user.name')))
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->required()
                            ->email()
                            ->unique(table: static::getModel(), ignorable: fn($record) => $record)
                            ->label(strval(__('filament-access-management::filament-access-management.field.user.email'))),

                        Forms\Components\TextInput::make('password')
                            ->same('passwordConfirmation')
                            ->password()
                            ->maxLength(255)
                            ->required(fn($component, $get, $livewire, $model, $record, $set, $state) => $record === null)
                            ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : '')
                            ->label(strval(__('filament-access-management::filament-access-management.field.user.password'))),

                        Forms\Components\TextInput::make('passwordConfirmation')
                            ->password()
                            ->dehydrated(false)
                            ->maxLength(255)
                            ->label(strval(__('filament-access-management::filament-access-management.field.user.confirm_password'))),

//                         Forms\Components\Select::make('roles')
//                             ->multiple()
//                             ->relationship('roles', 'name')
////                             ->options(Role::where('id', 2)->pluck('name', 'id'))
//                             ->preload()
//                             ->default([2]) // 使用陣列，因為你已經設定為多選
//                             ->label(strval(__('filament-access-management::filament-access-management.field.user.roles'))),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('is_admin', false))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label(strval(__('filament-access-management::filament-access-management.field.id'))),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('filament-access-management::filament-access-management.field.user.name'))),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('filament-access-management::filament-access-management.field.user.email'))),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->options([
                        'heroicon-o-check-circle',
                        'heroicon-o-x-circle' => fn($state): bool => $state === null,
                    ])
                    ->colors([
                        'success',
                        'danger' => fn($state): bool => $state === null,
                    ])
                    ->label(strval(__('filament-access-management::filament-access-management.field.user.verified_at'))),

                Tables\Columns\TagsColumn::make('roles.name')
                    ->label(strval(__('filament-access-management::filament-access-management.field.user.roles'))),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->label(strval(__('filament-access-management::filament-access-management.field.user.created_at'))),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {

        $relations = [];

        // 检查是否具有 'super-admin' 权限
        if (auth()->id()==1) {
            $relations[] = RelationManagers\RlRelationManager::class;
        }

        // 可以添加其他条件，或者直接返回
        return $relations;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUs::route('/'),
            'create' => Pages\CreateUs::route('/create'),
            'edit' => Pages\EditUs::route('/{record}/edit'),
        ];
    }

    public static function getNavigationIcon(): string
    {
        return config('filament-access-management.filament.navigationIcon.user') ?? parent::getNavigationIcon();
    }

    public static function getModel(): string
    {
        return Utils::getUserModel() ?? parent::getModel();
    }

    public static function getNavigationGroup(): ?string
    {
        return strval(__('filament-access-management::filament-access-management.section.group'));
    }

    public static function getLabel(): string
    {
        return strval(__('filament-access-management::filament-access-management.section.user'));
    }

    public static function getPluralLabel(): string
    {
        return strval(__('filament-access-management::filament-access-management.section.users'));
    }
}
