<?php

namespace App\Filament\Admin\Resources\RankingUserResource\Pages;

use App\Filament\Admin\Resources\RankingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRankingUser extends EditRecord
{
    protected static string $resource = RankingUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
