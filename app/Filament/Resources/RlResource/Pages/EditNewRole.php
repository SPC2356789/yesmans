<?php

namespace App\Filament\Resources\RlResource\Pages;

use App\Filament\Resources\RlResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use SolutionForest\FilamentAccessManagement\Facades\FilamentAuthenticate;
use SolutionForest\FilamentAccessManagement\Resources\RoleResource;
use SolutionForest\FilamentAccessManagement\Support\Utils;
class EditNewRole extends EditRecord

{
    protected static string $resource = RlResource::class;

    public function afterSave(): void
    {
        if (! is_a($this->record, Utils::getRoleModel())) {
            return;
        }

        FilamentAuthenticate::clearPermissionCache();
    }
}
