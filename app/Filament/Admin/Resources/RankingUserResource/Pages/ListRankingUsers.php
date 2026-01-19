<?php

namespace App\Filament\Admin\Resources\RankingUserResource\Pages;

use App\Filament\Admin\Resources\RankingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRankingUsers extends ListRecords
{
    protected static string $resource = RankingUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
