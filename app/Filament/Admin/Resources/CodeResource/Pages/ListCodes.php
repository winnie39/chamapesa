<?php

namespace App\Filament\Admin\Resources\CodeResource\Pages;

use App\Filament\Admin\Resources\CodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCodes extends ListRecords
{
    protected static string $resource = CodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
