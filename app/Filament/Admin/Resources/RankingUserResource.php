<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RankingUserResource\Pages;
use App\Filament\Admin\Resources\RankingUserResource\RelationManagers;
use App\Models\Ranking;
use App\Models\RankingUser;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RankingUserResource extends Resource
{
    protected static ?string $model = RankingUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')->label('Task')->options(User::get()->pluck('phone_number', 'id'))->searchable(),
                Select::make('ranking_id')->label('Task')->options(Ranking::get()->pluck('task_text', 'id'))->searchable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('ranking.task_text'),
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
            'index' => Pages\ListRankingUsers::route('/'),
            'create' => Pages\CreateRankingUser::route('/create'),
            'edit' => Pages\EditRankingUser::route('/{record}/edit'),
        ];
    }
}
