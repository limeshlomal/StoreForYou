<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public string $name = '';
    public string $group_name = '';
    public string $guard_name = 'web';
    public string $description = '';

    // Mock groups for the select dropdown
    public array $groups = [
        'User Management',
        'Role / Permissions Management',
        'Product Management',
        'Settings',
        'Purchase Management',
        'Invoices Management',
        'Reports',
        'Dashboard',
    ];

    public function save()
    {
        // Save logic would go here
        dd($this->name, $this->group_name, $this->guard_name, $this->description);
    }
}; ?>

<div class="flex flex-col gap-6">
    <flux:heading size="xl">Create New Permission</flux:heading>
    <flux:subheading>Define a new permission and assign it to a group.</flux:subheading>

    <flux:separator variant="subtle" />

    <form wire:submit="save" class="flex flex-col gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Permission Details -->
                <div class="space-y-6">
                    <flux:input wire:model="name" label="Permission Name" placeholder="e.g. create_users" description="Unique identifier for the permission." />

                    <div>
                        <flux:label>Group</flux:label>
                        <select wire:model="group_name" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 py-2 px-3">
                            <option value="">Select a Group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group }}">{{ $group }}</option>
                            @endforeach
                        </select>
                        <flux:error name="group_name" />
                    </div>
                </div>

                <!-- Additional Info -->
                 <div class="space-y-6">
                    <flux:input wire:model="guard_name" label="Guard Name" placeholder="web" />
                    
                    <flux:textarea wire:model="description" label="Description" placeholder="Optional description of what this permission allows." rows="4" />
                </div>
            </div>

        </div>

        <div class="flex justify-end gap-3">
            <flux:button variant="subtle" href="#">Cancel</flux:button>
            <flux:button variant="primary" type="submit">Create Permission</flux:button>
        </div>
    </form>
</div>
