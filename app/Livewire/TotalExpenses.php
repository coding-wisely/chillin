<?php

namespace App\Livewire;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TotalExpenses extends Component
{
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

    public function render()
    {
        // Calculate total incomes for the given date
        $totalIncomes = Expense::query()
            ->whereBetween('spent_at', [
                Carbon::parse($this->date)->startOfDay()->toDateTimeString(),
                Carbon::parse($this->date)->endOfDay()->toDateTimeString(),
            ])
            ->sum('amount');

        // Calculate total incomes for the previous date
        $yesterdayTotalIncomes = Expense::query()
            ->whereBetween('spent_at', [
                Carbon::parse($this->date)->subDay()->startOfDay()->toDateTimeString(),
                Carbon::parse($this->date)->subDay()->endOfDay()->toDateTimeString(),
            ])
            ->sum('amount');

        // Calculate the difference
        $difference = $totalIncomes - $yesterdayTotalIncomes;

        // Calculate the trend
        if ($totalIncomes == 0 && $yesterdayTotalIncomes == 0) {
            $trend = 'stale'; // No change, both days are zero
        } elseif ($totalIncomes >= $yesterdayTotalIncomes) {
            $trend = 'up';
        } else {
            $trend = 'down';
        }

        // Calculate the percentage difference
        if ($yesterdayTotalIncomes == 0) {
            $percentageDifference = ($totalIncomes != 0) ? 100 : 0;
        } else {
            $percentageDifference = ($difference / $yesterdayTotalIncomes) * 100;
        }

        return view('livewire.total-expenses', [
            'totalExpense' => Number::currency($totalIncomes, 'THB'),
            'percentageDifference' => $percentageDifference,
            'trend' => $trend,
        ]);
    }
}
