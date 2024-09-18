<?php

namespace App\Livewire;

use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TotalIncomes extends Component
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
        $totalIncomes = Income::query()
            ->whereBetween('received_at', [
                Carbon::parse($this->date)->startOfDay()->toDateTimeString(),
                Carbon::parse($this->date)->endOfDay()->toDateTimeString(),
            ])
            ->sum('amount');

        $yesterdayTotalIncomes = Income::query()
            ->whereBetween('received_at', [
                Carbon::parse($this->date)->subDay()->startOfDay()->toDateTimeString(),
                Carbon::parse($this->date)->subDay()->endOfDay()->toDateTimeString(),
            ])
            ->sum('amount');

        $difference = $totalIncomes - $yesterdayTotalIncomes;
        $trend = $difference >= 0 ? 'up' : 'down';
        // Calculate the trend
        if ($totalIncomes == 0 && $yesterdayTotalIncomes == 0) {
            $trend = 'stale'; // No change, both days are zero
        } elseif ($totalIncomes >= $yesterdayTotalIncomes) {
            $trend = 'up';
        } else {
            $trend = 'down';
        }

        $percentageDifference = $yesterdayTotalIncomes != 0 ? ($difference / $yesterdayTotalIncomes) * 100 : ($totalIncomes != 0 ? 100 : 0);

        return view('livewire.total-incomes', [
            'totalIncomes' => Number::currency($totalIncomes, 'THB'),
            'percentageDifference' => $percentageDifference,
            'trend' => $trend,
        ]);
    }
}
