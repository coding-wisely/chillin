<?php

namespace App\Livewire;

use App\Models\UserDayOff;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ListDaysOff extends Component implements HasForms, HasTable
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
            ->query(UserDayOff::query()
                ->whereBetween('day_off_date', [
                    Carbon::parse($this->date)->startOfDay()->toDateTimeString(),
                    Carbon::parse($this->date)->endOfDay()->toDateTimeString(),
                ]))
            ->columns([
                TextColumn::make('user.name')
                    ->label('Staff'),
            ])
            ->emptyStateHeading(__('custom.No user is on the day off'))
            ->heading(__('custom.Staff on the day off for :date', ['date' => Carbon::parse($this->date)->format('d M Y')]))
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

    public function render()
    {

        return view('livewire.list-days-off');
    }
}
