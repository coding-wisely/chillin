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
use Illuminate\Contracts\Support\Htmlable;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function getNavigationLabel(): string
    {
        return __('custom.Expense');
    }

    public function getTitle(): string | Htmlable
    {
        return __('custom.Expense');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\Select::make('category_id')
                        ->label(__('custom.Select Expense'))
                        ->relationship('category', 'title')
                        ->helperText(__('custom.Select the expense category.'))
                        ->required(),
                    Forms\Components\TextInput::make('amount')
                        ->label(__('custom.Amount'))
                        ->helperText(__('custom.The amount of the expense in thai baht.'))
                        ->required()
                        ->numeric(),
                ])->columnSpan(2),
                Section::make(__('custom.Details'))
                    ->description(__('custom.Add more details about the expense.'))
                    ->schema([
                        Forms\Components\DateTimePicker::make('spent_at')
                            ->label(__('custom.Spent at'))
                            ->native(false)
                            ->default(now()),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label(__('custom.Staff'))
                            ->default(auth()->id())
                            ->disabled(),
                    ])->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.title')
                    ->label(__('custom.Category'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('custom.Staff'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('custom.Amount'))
                    ->money('THB')
                    ->summarize(Sum::make()
                        ->money('THB')
                    ->label(__('custom.Total') . ':'))->numeric()->sortable(),
                Tables\Columns\TextColumn::make('spent_at')
                    ->label(__('custom.Spent at'))
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
