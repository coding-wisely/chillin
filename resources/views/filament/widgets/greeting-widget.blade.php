@php
    $user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-start gap-x-3">
            <div class="grid gap-4 justify-items-center">

                <form
                    action="{{ filament()->getLogoutUrl() }}"
                    method="post"
                    class="my-auto"
                >
                    @csrf

                    <x-filament::button
                        color="gray"
                        icon="heroicon-m-arrow-left-on-rectangle"
                        icon-alias="panels::widgets.account.logout-button"
                        labeled-from="sm"
                        tag="button"
                        type="submit"
                    >
                        {{ __('filament-panels::widgets/account-widget.actions.logout.label') }}
                    </x-filament::button>
                </form>

                <x-filament-panels::avatar.user size="lg" :user="$user"/>

            </div>
            <div class="flex flex-1 flex-col space-y-6">
                <div
                    class=" flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white text-right"
                >
                    สวัสดีครับคุณ {{ filament()->getUserName($user) }}
                </div>
                <div class="italic text-sm text-right">
                    {!! $hello !!}
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
