<div class="grid grid-cols-3 border-b-4 gap-4">
    <div class="uppercase text-green-800 col-span-1">
        Income
    </div>
    <div class="flex justify-between col-span-2">
        <div>
            {{ $totalIncomes }}
        </div>
        <x-trend :trend="$trend" :percentageDifference="$percentageDifference"/>
    </div>
</div>

