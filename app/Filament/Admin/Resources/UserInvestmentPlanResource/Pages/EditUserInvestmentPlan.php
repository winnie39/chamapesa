<?php

namespace App\Filament\Admin\Resources\UserInvestmentPlanResource\Pages;

use App\Filament\Admin\Resources\UserInvestmentPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserInvestmentPlan extends EditRecord
{
    protected static string $resource = UserInvestmentPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
