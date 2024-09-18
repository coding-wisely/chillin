<div class="grid grid-cols-3 border-b-4 gap-4">
    <div class="uppercase text-green-800 col-span-1">
        Saldo
    </div>
    <div class="flex justify-between col-span-2">
        <div>
            {{ $totalSaldo }}
        </div>

        <x-trend :trend="$trend" :percentageDifference="$percentageDifference"/>
    </div>
</div>
