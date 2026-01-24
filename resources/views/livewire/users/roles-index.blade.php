<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    // Mock data for roles
    public array $roles = [
        ['id' => 1, 'name' => 'Admin', 'guard_name' => 'web', 'permissions_count' => 50, 'created_at' => '2024-01-01'],
        ['id' => 2, 'name' => 'Manager', 'guard_name' => 'web', 'permissions_count' => 30, 'created_at' => '2024-01-15'],
        ['id' => 3, 'name' => 'Cashier', 'guard_name' => 'web', 'permissions_count' => 10, 'created_at' => '2024-02-01'],
    ];

    public function delete($id)
    {
        // Delete logic would go here
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl">Roles Management</flux:heading>
            <flux:subheading>Manage user roles and their associated permissions.</flux:subheading>
        </div>
        <flux:button href="{{ route('users.roles.create') }}" variant="primary" icon="plus" wire:navigate>Add New Role</flux:button>
    </div>

    <flux:separator variant="subtle" />

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-medium text-slate-900 dark:text-white">Role Name</th>
                        <th class="px-6 py-4 font-medium text-slate-500 dark:text-slate-400">Guard</th>
                        <th class="px-6 py-4 font-medium text-slate-500 dark:text-slate-400">Permissions</th>
                        <th class="px-6 py-4 font-medium text-slate-500 dark:text-slate-400">Created At</th>
                        <th class="px-6 py-4 font-medium text-slate-500 dark:text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($roles as $role)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-colors">
                            <td class="px-6 py-4 text-slate-900 dark:text-white font-medium">{{ $role['name'] }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30">
                                    {{ $role['guard_name'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $role['permissions_count'] }} Permissions</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $role['created_at'] }}</td>
                            <td class="px-6 py-4 text-right">
                                <flux:dropdown>
                                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />

                                    <flux:menu>
                                        <flux:menu.item icon="pencil-square">Edit</flux:menu.item>
                                        <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $role['id'] }})">Delete</flux:menu.item>
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
