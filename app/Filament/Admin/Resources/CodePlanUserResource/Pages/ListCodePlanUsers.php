<?php

namespace App\Filament\Admin\Resources\CodePlanUserResource\Pages;

use App\Filament\Admin\Resources\CodePlanUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCodePlanUsers extends ListRecords
{
    protected static string $resource = CodePlanUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
