<?php

namespace App\Filament\Admin\Resources\CodePlanResource\Pages;

use App\Filament\Admin\Resources\CodePlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCodePlan extends EditRecord
{
    protected static string $resource = CodePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
