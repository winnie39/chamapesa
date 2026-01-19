<?php

namespace App\Filament\Admin\Resources\CodePlanResource\Pages;

use App\Filament\Admin\Resources\CodePlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCodePlans extends ListRecords
{
    protected static string $resource = CodePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
