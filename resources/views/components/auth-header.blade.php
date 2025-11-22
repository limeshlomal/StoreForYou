@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <flux:heading size="xl"><x-app-logo /></flux:heading>
    <flux:subheading>{{ $description }}</flux:subheading>
</div>
