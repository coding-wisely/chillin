<?php

namespace App\Livewire;

use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TotalSaldo extends Component
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
        $date = Carbon::parse($this->date);

        $totalIncomes = $this->getAmountSum(Income::class, 'received_at', $date);
        $totalExpenses = $this->getAmountSum(Expense::class, 'spent_at', $date);

        $yesterdayDate = $date->copy()->subDay();
        $yesterdayTotalIncomes = $this->getAmountSum(Income::class, 'received_at', $yesterdayDate);

        $totalSaldo = $totalIncomes - $totalExpenses;

        $percentageDifference = $this->calculatePercentageDifference($totalIncomes, $yesterdayTotalIncomes);

        $incomeDifference = $totalIncomes - $yesterdayTotalIncomes;
        $trend = $incomeDifference > 0 ? 'up' : 'down';


        return view('livewire.total-saldo', [
            'totalSaldo' => Number::currency($totalSaldo, 'THB'),
            'percentageDifference' => $percentageDifference,
            'trend' => $trend
        ]);

    }

    private function getAmountSum($modelClass, $dateField, $date)
    {
        return $modelClass::query()
            ->whereBetween($dateField, [
                $date->startOfDay()->toDateTimeString(),
                $date->endOfDay()->toDateTimeString()
            ])
            ->sum('amount');
    }
    private function calculatePercentageDifference($currentValue, $previousValue)
    {
        if ($previousValue != 0) {
            return (($currentValue - $previousValue) / $previousValue) * 100;
        } else {
            return ($currentValue != 0) ? 100 : 0;
        }
    }
}
