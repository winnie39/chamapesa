<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CodePlanResource\Pages;
use App\Filament\Admin\Resources\CodePlanResource\RelationManagers;
use App\Models\CodePlan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CodePlanResource extends Resource
{
    protected static ?string $model = CodePlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->rules('required|string'),
                TextInput::make('price')->rules('required|numeric'),
                TextInput::make('period_in_hours')->rules('required|numeric'),
                TextInput::make('winning_amount_per_code')->rules('required|numeric'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('price')->money(config('app.currency')),
                TextColumn::make('period_in_hours'),
                TextColumn::make('winning_amount_per_code')->money(config('app.currency')),
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
            'index' => Pages\ListCodePlans::route('/'),
            'create' => Pages\CreateCodePlan::route('/create'),
            'edit' => Pages\EditCodePlan::route('/{record}/edit'),
        ];
    }
}
