<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\UserDayOffResource\Pages;
use App\Models\UserDayOff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class UserDayOffResource extends Resource
{
    protected static ?string $model = UserDayOff::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    public static function getNavigationLabel(): string
    {
        return __('custom.Day off');
    }

    public function getTitle(): string|Htmlable
    {
        return __('custom.Day off');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(__('custom.Staff'))
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('day_off_date')
                    ->label(__('custom.Day off date'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('custom.Staff'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day_off_date')
                    ->label(__('custom.Day off date'))
                    ->date()
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
                Tables\Actions\EditAction::make()
                ->label(__('custom.Edit Day off')),
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
            'index' => Pages\ListUserDayOffs::route('/'),
            'create' => Pages\CreateUserDayOff::route('/create'),
            'edit' => Pages\EditUserDayOff::route('/{record}/edit'),
        ];
    }
}
