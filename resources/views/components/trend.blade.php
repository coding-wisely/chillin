@props([
    'trend',
    'percentageDifference'
])

<div {{ $attributes }}>
    @if($trend === 'up')
        <div class="inline-flex items-baseline rounded-full bg-green-100 px-2.5 py-0.5 text-sm font-medium text-green-500 md:mt-2 lg:mt-0">
            <x-heroicon-o-face-smile class="h-5 w-5 flex-shrink-0 self-center text-green-500"/>
        </div>
    @elseif($trend === 'down')




        <div class="inline-flex items-baseline rounded-full bg-red-100 px-2.5 py-0.5 text-sm font-medium text-red-800 md:mt-2 lg:mt-0">
            <x-heroicon-o-face-frown class="h-5 w-5 flex-shrink-0 self-center text-red-500"/>
        </div>



    @else
        <div class="inline-flex items-baseline rounded-full bg-yellow-100 px-2.5 py-0.5 text-sm font-medium text-yellow-800 md:mt-2 lg:mt-0">
            <x-heroicon-o-minus-circle class="h-5 w-5 flex-shrink-0 self-center text-yellow-500"/>
            <span class="sr-only"> No change </span>
        </div>
    @endif
</div>
