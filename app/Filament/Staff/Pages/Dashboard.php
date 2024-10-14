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
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Mail;

class Dashboard extends BaseDashboard implements HasActions, HasForms
{
    use HasFiltersForm;
    use InteractsWithActions;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.staff.pages.dashboard';

    public string $date = '';

    public static function getNavigationLabel(): string
    {
        return __('custom.Dashboard');
    }

    public function getTitle(): string | Htmlable
    {
        return __('custom.Dashboard');
    }

    public function createLadyDrink()
    {
        return Action::make('LADY DRINKS')
            ->label(__('custom.Lady drinks'))
            ->url(fn(): string => url('/staff/ladydrinks/create'))
            ->icon('heroicon-o-sparkles')
            ->iconSize('w-10 h-10');
    }
    public function createDayOff()
    {
        return Action::make('Day off')
            ->label(__('custom.Day off'))
            ->color(fn() => Color::Zinc)
            ->url(fn(): string => url('/staff/user-day-offs/create'))
            ->icon('heroicon-o-rocket-launch')
            ->iconSize('w-10 h-10');
    }

    public function createExpense()
    {
        return Action::make('EXPENSE')
            ->label(__('custom.Expense'))
            ->color(fn() => Color::Amber)
            ->url(fn(): string => url('/staff/expenses/create'))
            ->icon('heroicon-o-rectangle-stack')
            ->iconSize('w-10 h-10');
    }

    public function createIncome()
    {
        return Action::make('INCOME')
            ->label(__('custom.Income'))
            ->color(fn() => Color::Sky)
            ->url(fn(): string => url('/staff/incomes/create'))
            ->icon('heroicon-o-banknotes')
            ->iconSize('w-10 h-10');

    }

    public function createReport()
    {
        return Action::make('createReport')
            ->label(__('custom.Create Report For Milan'))
            ->modalHeading('')
            ->color(fn() => Color::Pink)
            ->icon('heroicon-o-presentation-chart-bar')
            ->modalContent(function () {
                return view('livewire.staff.report', [
                    'date' => $this->date,
                ]);
            })
            ->modalSubmitActionLabel(__('custom.Send report to Milan'))
            ->iconSize('w-10 h-10')
            ->action(function () {
                $date = $this->date;
                // Call a function to send the report to Milan
                $this->sendReportToMilan($date);
            });
    }

    public function mount(): void
    {
        $this->date = Carbon::now()->startOfDay();
    }

    private function sendReportToMilan(string $date)
    {
        Mail::to(config('app.admin_mail'))->send(new DailyReport($date));
    }
}
