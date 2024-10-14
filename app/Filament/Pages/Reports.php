<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static string $view = 'filament.pages.reports';

    public string $date = '';

    public static function getNavigationLabel(): string
    {
        return __('custom.Reports');
    }

    public function getTitle(): string|Htmlable
    {
        return __('custom.Reports');
    }


    public function mount(): void
    {
        $date = request()->query('date');
        if ($date) {
            // date is passed in unix and need to parse it to start of date
            $this->date = Carbon::createFromTimestamp($date)->startOfDay();
        } else {
            $this->date = Carbon::now()->startOfDay();
        }
    }
}
