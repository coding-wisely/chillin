<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\LadydrinkResource\Pages;
use App\Models\Ladydrink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class LadydrinkResource extends Resource
{
    protected static ?string $model = Ladydrink::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';


    public static function getNavigationLabel(): string
    {
        return __('custom.Lady drinks');
    }

    public function getTitle(): string | Htmlable
    {
        return __('custom.Lady drinks');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label(__('custom.Amount'))
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('user_id')
                    ->label(__('custom.Staff'))
                    ->helperText(__('custom.Select the staff who sold the ladydrink.'))
                    ->relationship('user', 'name')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('THB')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('THB')->label('Total:'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListLadydrinks::route('/'),
            'create' => Pages\CreateLadydrink::route('/create'),
            'edit' => Pages\EditLadydrink::route('/{record}/edit'),
        ];
    }
}
