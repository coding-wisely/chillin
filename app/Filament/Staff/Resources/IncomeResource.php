<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\IncomeResource\Pages;
use App\Filament\Staff\Resources\IncomeResource\RelationManagers;
use App\Models\Income;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IncomeResource extends Resource
{
    protected static ?string $model = Income::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\Select::make('category_id')
                        ->label('Select Income Category')
                        ->relationship('category', 'title')
                        ->helperText('Select the category for this record.')
                        ->required(),
                    Forms\Components\TextInput::make('amount')
                        ->helperText('The amount of the income in thai baht. e.g 100')
                        ->numeric()
                        ->required(),
                ])->columnSpan(2),
                Section::make('Details')
                    ->description('Add more details about the expense.')
                    ->disabled()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Staff')
                            ->default(auth()->id()),
                        Forms\Components\DateTimePicker::make('spent_at')
                            ->native(false)
                            ->default(now()),
                    ])->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')

                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')->money('THB')->summarize(Sum::make()->money('THB')->label('Total:')),
                Tables\Columns\TextColumn::make('received_at')
                    ->sortable(),
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
            'index' => Pages\ListIncomes::route('/'),
            'create' => Pages\CreateIncome::route('/create'),
            'edit' => Pages\EditIncome::route('/{record}/edit'),
        ];
    }
}
