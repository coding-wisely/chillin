<?php

namespace App\Filament\Staff\Pages;

use App\Mail\DailyReport;
use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Mail;

class Dashboard extends BaseDashboard implements HasActions, HasForms
{
    use HasFiltersForm;
    use InteractsWithActions;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.staff.pages.dashboard';

    public string $date = '';

    public function createLadyDrink()
    {
        return Action::make('LADY DRINKS')
            ->url(fn(): string => url('/staff/ladydrinks/create'))
            ->icon('heroicon-o-plus-circle')
            ->iconSize('w-10 h-10');
    }

    public function createExpense()
    {
        return Action::make('EXPENSE')
            ->url(fn(): string => url('/staff/expenses/create'))
            ->icon('heroicon-o-plus-circle')
            ->iconSize('w-10 h-10');
    }

    public function createIncome()
    {
        return Action::make('INCOME')
            ->color(fn() => Color::Sky)
            ->url(fn(): string => url('/staff/incomes/create'))
            ->icon('heroicon-o-plus-circle')
            ->iconSize('w-10 h-10');

    }

    public function createReport()
    {
        return Action::make('createReport')
            ->modalHeading('')
            ->color(fn() => Color::Pink)
            ->icon('heroicon-o-presentation-chart-bar')
            ->modalContent(function () {
                return view('livewire.staff.report', [
                    'date' => $this->date,
                ]);
            })
            ->modalSubmitActionLabel('Send report to Milan')
            ->iconSize('w-10 h-10')
            ->action(fn() => defer(function () {

                $totalIncome = Income::where('date', $this->date)->sum('amount');
                $totalExpense = Expense::where('date', $this->date)->sum('amount');
                $totalProfit = $totalIncome - $totalExpense;

                $reportData = [
                    'date' => $this->date,
                    'totalIncome' => $totalIncome,
                    'totalExpense' => $totalExpense,
                    'totalProfit' => $totalProfit,
                ];
                $date = $this->date;
                // Call a function to send the report to Milan
                return $this->sendReportToMilan($date);
            }));
    }

    public function mount(): void
    {
        $this->date = Carbon::now()->startOfDay();
    }

    private function sendReportToMilan(string $date)
    {
        Mail::to('milan@chillinpattaya.com')->send(new DailyReport($date));
    }
}
