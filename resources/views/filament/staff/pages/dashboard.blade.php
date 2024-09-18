<x-filament-panels::page>
    <div class="">
        This is testing tailwindcss div
    </div>
    <div class="grid h-32 self-center bg-red-500">
        {{ $this->createReport() }}
    </div>
    <div class="grid md:grid-cols-3 gap-4 h-32">
        {{ $this->createLadyDrink() }}
        {{ $this->createExpense() }}
        {{ $this->createIncome() }}
    </div>
</x-filament-panels::page>
