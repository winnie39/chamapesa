<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InvestmentPlanResource\Pages;
use App\Filament\Admin\Resources\InvestmentPlanResource\RelationManagers;
use App\Models\InvestmentPlan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvestmentPlanResource extends Resource
{
    protected static ?string $model = InvestmentPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->rules('required'),
                TextInput::make('rate')->rules('required|numeric'),
                TextInput::make('price')->rules('required|numeric'),
                TextInput::make('period_in_hours')->rules('required|numeric'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('rate'),
                TextColumn::make('price'),
                TextColumn::make('period_in_hours'),
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
            'index' => Pages\ListInvestmentPlans::route('/'),
            'create' => Pages\CreateInvestmentPlan::route('/create'),
            'edit' => Pages\EditInvestmentPlan::route('/{record}/edit'),
        ];
    }
}
