<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    // Mock data for permissions
    public array $permissions = [
        ['id' => 1, 'name' => 'view_users', 'group' => 'User Management', 'guard_name' => 'web'],
        ['id' => 2, 'name' => 'create_users', 'group' => 'User Management', 'guard_name' => 'web'],
        ['id' => 3, 'name' => 'create_products', 'group' => 'Product Management', 'guard_name' => 'web'],
        ['id' => 4, 'name' => 'manage_settings', 'group' => 'Settings', 'guard_name' => 'web'],
    ];

    public function delete($id)
    {
        // Delete logic would go here
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl">Permissions Management</flux:heading>
            <flux:subheading>View and manage all system permissions and their groups.</flux:subheading>
        </div>
        <flux:button href="{{ route('users.permissions.create') }}" variant="primary" icon="plus" wire:navigate>Add New Permission</flux:button>
    </div>

    <flux:separator variant="subtle" />

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-medium text-slate-900 dark:text-white">Permission Name</th>
                        <th class="px-6 py-4 font-medium text-slate-500 dark:text-slate-400">Group</th>
                        <th class="px-6 py-4 font-medium text-slate-500 dark:text-slate-400">Guard</th>
                        <th class="px-6 py-4 font-medium text-slate-500 dark:text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($permissions as $permission)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-colors">
                            <td class="px-6 py-4 text-slate-900 dark:text-white font-medium">{{ $permission['name'] }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10 dark:bg-slate-400/10 dark:text-slate-400 dark:ring-slate-400/20">
                                    {{ $permission['group'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30">
                                    {{ $permission['guard_name'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <flux:dropdown>
                                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />

                                    <flux:menu>
                                        <flux:menu.item icon="pencil-square">Edit</flux:menu.item>
                                        <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $permission['id'] }})">Delete</flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
