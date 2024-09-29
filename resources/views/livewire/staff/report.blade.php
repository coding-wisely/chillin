<div class="flex w-full h-full flex-col px-4 sm:px-6 lg:px-8 space-y-10">
    <div class="flex w-full flex-col px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="p-2">
            <div class="grid sm:grid-cols-3 gap-5">
                <!-- Summary Section -->
                <div class="flex flex-col gap-3 col-span-2">
                    <livewire:total-incomes :date="$date" key="{{ $date }}"/>
                    <livewire:total-expenses :date="$date" key="{{ $date }}"/>
                    <livewire:total-saldo :date="$date" key="{{ $date }}"/>
                </div>
                <div class="self-start ">
                    <input type="date"
                           wire:model.live="date"
                           class="border border-rose-300 rounded-md p-2 w-full ring-0 focus:ring-0 focus:border-rose-300 focus:outline-none"
                           placeholder="Select Date">
                </div>
            </div>

        </div>
    </div>

    <!-- Details Section -->
    <div class="grid sm:grid-cols-2 gap-6">
        <!-- Incomes List -->
        <livewire:incomes.list-incomes :date="$date" key="{{ $date }}"/>

        <!-- Expenses List -->
        <livewire:expenses.list-expenses :date="$date" key="{{ $date }}"/>
    </div>
    <div class="grid sm:grid-cols-2 gap-6">
        <!-- Lady Drinks Section -->
        <livewire:list-lady-drinks :date="$date" key="{{ $date }}"/>

        <!-- Users on Days Off Section -->
        <livewire:list-days-off :date="$date" key="{{ $date }}"/>
    </div>
</div>
