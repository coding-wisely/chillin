<?php

namespace App\Livewire\Incomes;

use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ListIncomes extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;


    public $date;

    #[Computed]
    public function getDate(): string
    {
        return Carbon::parse($this->date)->format('Y-m-d');
    }

    public function mount($date)
    {
        $this->date = $date;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Income::query()
                ->whereBetween('received_at', [
                    Carbon::parse($this->date)->startOfDay()->toDateTimeString(),
                    Carbon::parse($this->date)->endOfDay()->toDateTimeString()
                ]))
            ->columns([
                Tables\Columns\TextColumn::make('category.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Staff'),
                Tables\Columns\TextColumn::make('amount')
                    ->sortable(),
                TextColumn::make('amount')
                    ->money('THB')
                    ->summarize(Sum::make()->money('THB')->label('Total:'))

            ])
            ->heading('Incomes for ' . Carbon::parse($this->date)->format('d M Y'))
            ->paginated(false)
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.incomes.list-incomes');
    }
}
