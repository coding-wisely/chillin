import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Staff/**/*.php',
        './resources/views/filament/staff/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './resources/views/livewire/**/*.blade.php',
        './resources/views/components/**/*.blade.php'
    ],
}
