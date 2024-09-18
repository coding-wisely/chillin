<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomeResource\Pages;
use App\Filament\Resources\IncomeResource\RelationManagers;
use App\Models\Income;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class IncomeResource extends Resource
{
    protected static ?string $model = Income::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'title')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('user.name')
                    ->label('Staff name'),
                Group::make('category.title')
                    ->label('Category name'),
                Group::make('created_at')
                    ->label('Date')
                    ->date()
                    ->collapsible(true)
            ])
            ->defaultGroup(Group::make('created_at')
                ->date())

            ->columns([
                Tables\Columns\TextColumn::make('category.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('THB', locale: 'th')
                    ->summarize(Sum::make()->money('THB', locale: 'th')
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\Fieldset::make('Income between dates')
                            ->schema([
                                Forms\Components\DatePicker::make('from')
                                    ->native(false)
                                    ->label('From')
                                    ->required(),
                                Forms\Components\DatePicker::make('to')
                                    ->native(false)
                                    ->label('To')
                                    ->required(),
                            ])->columns(2),
                    ])
                ->query(function (Builder $query, array $data) {
                    return $query->when($data['from']??null, function ($query) use ($data) {
                        $query->whereBetween('created_at', [
                            Carbon::parse($data['from'])->startOfDay(),
                            Carbon::parse($data['to'])->endOfDay(),
                        ]);
                    });
                    $query->whereBetween('created_at', [
                        Carbon::parse($data['from'])->startOfDay(),
                        Carbon::parse($data['to'])->endOfDay(),
                    ]);
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
