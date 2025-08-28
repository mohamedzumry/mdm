<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        <x-mary-toast />
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
