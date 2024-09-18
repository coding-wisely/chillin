<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\ExpenseResource\Pages;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\Select::make('category_id')
                        ->label('Select Expense')
                        ->relationship('category', 'title')
                        ->helperText('Select the expense.')
                        ->required(),
                    Forms\Components\TextInput::make('amount')
                        ->helperText('The amount of the expense in thai baht.')
                        ->required()
                        ->numeric(),
                ])->columnSpan(2),
                Section::make('Details')
                    ->description('Add more details about the expense.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Staff')
                            ->default(auth()->id())->disabled(),
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
                Tables\Columns\TextColumn::make('spent_at')
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
