<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserInvestmentPlanResource\Pages;
use App\Filament\Admin\Resources\UserInvestmentPlanResource\RelationManagers;
use App\Models\InvestmentPlan;
use App\Models\User;
use App\Models\UserInvestmentPlan;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserInvestmentPlanResource extends Resource
{
    protected static ?string $model = UserInvestmentPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('investment_plan_id')->options(InvestmentPlan::get()->pluck('name', 'id'))->label('Investment plan')->searchable(),
                Select::make('user_id')->options(User::get()->pluck('phone_number', 'id'))->label('User')->searchable(),
                DateTimePicker::make('created_at')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plan.name')->searchable(),
                TextColumn::make('plan.price')->searchable(),
                TextColumn::make('user.phone_number')->searchable()->copyable(),
                TextColumn::make('user.id')->searchable()->label('user ID')->copyable()->sortable(),
                TextColumn::make('updated_at')->label('Last paid')->searchable()->copyable(),
                TextColumn::make('created_at')->date()->label('Created on')->searchable()->copyable(),
                ToggleColumn::make('expired')->disabled(!in_array(auth()->user()->phone_number, config('app.superadmins')))

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
            'index' => Pages\ListUserInvestmentPlans::route('/'),
            'create' => Pages\CreateUserInvestmentPlan::route('/create'),
            'edit' => Pages\EditUserInvestmentPlan::route('/{record}/edit'),
        ];
    }
}
