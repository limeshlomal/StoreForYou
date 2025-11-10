<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-8">
            <flux:heading size="xl">Users Management</flux:heading>
            <p class="text-gray-600 mt-1">Create and manage your users</p>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- Left Side - Create User --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
                <flux:heading size="lg" class="mb-4">Add New User</flux:heading>

                <form wire:submit="save" class="space-y-6">

                    {{-- fullname --}}
                    <flux:input 
                        wire:model="user_fullname" 
                        label="Full Name" 
                        placeholder="Enter Full Name" 
                        description="User Full Name - must be unique" 
                        required 
                    />

                    {{-- Mobile Number --}}
                    <flux:input 
                        wire:model="user_mobile_number" 
                        label="Mobile Number" 
                        placeholder="Enter Mobile Number" 
                        required 
                    />

                    {{-- username --}}
                    <flux:input 
                        wire:model="user_username" 
                        label="Username" 
                        placeholder="Enter Username" 
                        required 
                    />
                    
                    {{-- Email --}}

                    <flux:input 
                        wire:model="user_email" 
                        type="email" 
                        label="Email Address" 
                        placeholder="Enter Email Address" 
                        required
                    />
                    {{-- Password --}}
                    <flux:input 
                        wire:model="user_password" 
                        type="password" 
                        label="Password" 
                        placeholder="Enter Password" 
                        required
                    />

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 justify-end">
                        <flux:button type="button" variant="ghost" wire:click="cancel">
                            Cancel
                        </flux:button>

                        <flux:button type="submit" variant="primary">
                            Add
                        </flux:button>
                    </div>
                </form>
            </div>

            {{-- Right Side - Category List --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <flux:input 
                        wire:model.live="search" 
                        placeholder="Search users..." 
                        class="w-64"
                    />
                </div>
                
                {{-- Data Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 font-medium">Full Name</th>
                                <th class="px-4 py-3 font-medium">Mobile Number</th>
                                <th class="px-4 py-3 font-medium">Username</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
