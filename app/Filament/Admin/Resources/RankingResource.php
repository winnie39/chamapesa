<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RankingResource\Pages;
use App\Filament\Admin\Resources\RankingResource\RelationManagers;
use App\Models\Ranking;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RankingResource extends Resource
{
    protected static ?string $model = Ranking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('task_text')->rules('required|string'),
                // TextInput::make('reward_text')->rules('required|string'),
                TextInput::make('reward')->rules('required|numeric'),
                TextInput::make('task')->rules('required|numeric'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('task_text'),
                TextColumn::make('reward_text'),
                TextColumn::make('reward'),
                TextColumn::make('task'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRankings::route('/'),
            'create' => Pages\CreateRanking::route('/create'),
            'edit' => Pages\EditRanking::route('/{record}/edit'),
        ];
    }
}
