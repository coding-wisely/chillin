/** @type {import('tailwindcss').Config} */
import preset_email from  'tailwindcss-preset-email'
module.exports = {
    presets: [
        preset_email
    ],
    content: [
        '../resources/views/filament/staff/**/*.blade.php',
        '../resources/views/livewire/incomes/*.blade.php',
        '../resources/views/livewire/expenses/*.blade.php',
        '../resources/views/components/trend.blade.php',
        './src/**/*.html',
    ],
    theme: {
        extend: {
            fontFamily: {
                inter: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', '"Segoe UI"', 'sans-serif'],
            },
        },
    },
}
