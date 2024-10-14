<x-filament-panels::page>
    <div class="grid h-32 self-center bg-red-500">
        {{ $this->createReport() }}
    </div>

    <div class="grid md:grid-cols-2 gap-4 h-32">
        {{ $this->createExpense() }}
        {{ $this->createIncome() }}
        {{ $this->createLadyDrink() }}
        {{ $this->createDayOff() }}
    </div>
</x-filament-panels::page>
