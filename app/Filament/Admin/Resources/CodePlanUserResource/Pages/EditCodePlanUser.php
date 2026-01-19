<?php

namespace App\Filament\Admin\Resources\CodePlanUserResource\Pages;

use App\Filament\Admin\Resources\CodePlanUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCodePlanUser extends EditRecord
{
    protected static string $resource = CodePlanUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
