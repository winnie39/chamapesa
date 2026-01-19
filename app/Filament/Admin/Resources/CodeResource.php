<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CodeResource\Pages;
use App\Filament\Admin\Resources\CodeResource\RelationManagers;
use App\Models\Code;
use App\Models\CodePlan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CodeResource extends Resource
{
    protected static ?string $model = Code::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')->label('Won By')->options(User::get()->pluck('phone_number', 'id'))->searchable()->rules('nullable'),
                Select::make('code_plan_id')->label('Plan')->options(CodePlan::get()->pluck('name', 'id'))->searchable()->rules('required'),
                TextInput::make('code')->rules('required|string'),
                DateTimePicker::make('expiry')->rules('required'),
                Toggle::make('used')->rules('required'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.phone_number')->label('Won By'),
                TextColumn::make('plan.name')->label('Plan'),
                TextColumn::make('code')->searchable(),
                TextColumn::make('expiry')->date(),
                ToggleColumn::make('used')->sortable(),
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
            'index' => Pages\ListCodes::route('/'),
            'create' => Pages\CreateCode::route('/create'),
            'edit' => Pages\EditCode::route('/{record}/edit'),
        ];
    }
}
