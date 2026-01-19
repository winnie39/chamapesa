<?php

namespace App\Filament\Admin\Resources\UserInvestmentPlanResource\Pages;

use App\Filament\Admin\Resources\UserInvestmentPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserInvestmentPlans extends ListRecords
{
    protected static string $resource = UserInvestmentPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
