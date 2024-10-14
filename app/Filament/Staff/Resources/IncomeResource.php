<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\IncomeResource\Pages;
use App\Models\Income;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class IncomeResource extends Resource
{
    protected static ?string $model = Income::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationLabel(): string
    {
        return __('custom.Income');
    }

    public function getTitle(): string | Htmlable
    {
        return __('custom.Income');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\Select::make('category_id')
                        ->label(__('custom.Select Income'))
                        ->relationship('category', 'title')
                        ->helperText(__('custom.Select the category for this record.'))
                        ->required(),
                    Forms\Components\TextInput::make('amount')
                        ->label(__('custom.Amount'))
                        ->helperText(__('custom.The amount of the income in thai baht. e.g 100'))
                        ->numeric()
                        ->required(),
                ])->columnSpan(2),
                Section::make(__('custom.Details'))
                    ->label(__('custom.Details'))
                    ->description(__('custom.Add more details about the income.'))
                    ->disabled()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label(__('custom.Staff'))
                            ->default(auth()->id()),
                        Forms\Components\DateTimePicker::make('received_at')
                            ->label(__('custom.Received at'))
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
                    ->label(__('custom.Income'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('custom.Staff'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('custom.Amount'))
                    ->money('THB')
                    ->summarize(Sum::make()->money('THB')
                        ->label(__('custom.Total') . ':')),
                Tables\Columns\TextColumn::make('received_at')
                    ->label(__('custom.Received at'))
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
