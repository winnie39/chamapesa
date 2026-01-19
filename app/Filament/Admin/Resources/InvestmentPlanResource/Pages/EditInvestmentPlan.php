<?php

namespace App\Filament\Admin\Resources\InvestmentPlanResource\Pages;

use App\Filament\Admin\Resources\InvestmentPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvestmentPlan extends EditRecord
{
    protected static string $resource = InvestmentPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
