<?php

namespace App\Livewire;

use App\Models\Ladydrink;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ListLadyDrinks extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $date;

    #[Computed]
    public function getDate(): string
    {
        return Carbon::parse($this->date)->format('Y-m-d');
    }

    public function mount($date): void
    {
        $this->date = $date;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Ladydrink::query()
                ->whereBetween('created_at', [
                    Carbon::parse($this->date)->startOfDay()->toDateTimeString(),
                    Carbon::parse($this->date)->endOfDay()->toDateTimeString(),
                ]))
            ->columns([
                TextColumn::make('user.name')
                    ->label('Staff'),
                TextColumn::make('amount')
                    ->sortable(),
                TextColumn::make('amount')
                    ->money('THB')
                    ->summarize(Sum::make()->money('THB')->label('Total:')),

            ])
            ->emptyStateHeading(__('custom.No lady drinks found'))
            ->heading(__('custom.Lady drinks for :date', ['date' => Carbon::parse($this->date)->format('d M Y')]))
            ->paginated(false)
            ->filters([
                //
            ])
            ->actions([
                // Action::make('edit'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.list-lady-drinks');
    }
}
