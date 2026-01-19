<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CodePlanUserResource\Pages;
use App\Filament\Admin\Resources\CodePlanUserResource\RelationManagers;
use App\Models\CodePlan;
use App\Models\CodePlanUser;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CodePlanUserResource extends Resource
{
    protected static ?string $model = CodePlanUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')->label('Won By')->options(User::get()->pluck('phone_number', 'id'))->searchable()->rules('nullable'),
                Select::make('code_plan_id')->label('Plan')->options(CodePlan::get()->pluck('name', 'id'))->searchable()->rules('required'),
                DateTimePicker::make('expiry_date')->rules('required'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.phone_number')->copyable(),
                TextColumn::make('plan.name'),
                TextColumn::make('expiry_date')->date()
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
            'index' => Pages\ListCodePlanUsers::route('/'),
            'create' => Pages\CreateCodePlanUser::route('/create'),
            'edit' => Pages\EditCodePlanUser::route('/{record}/edit'),
        ];
    }
}
