<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new #[Layout('components.layouts.app')] class extends Component {
    public string $name = '';
    public array $selectedPermissions = [];
    public bool $selectAll = false;
    public array $permissionsByGroup = [];

    public function mount()
    {
        $this->permissionsByGroup = Permission::all()
            ->map(fn($p) => [
                'id' => $p->name,
                'label' => ucfirst(str_replace('_', ' ', $p->name)),
                'group' => $p->group ?? 'General',
            ])
            ->groupBy('group')
            ->toArray();
    }
   

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPermissions = collect($this->permissionsByGroup)
                ->flatten(1)
                ->pluck('id')
                ->values()
                ->all();
        } else {
            $this->selectedPermissions = [];
        }
    }
    
    public function updatedSelectedPermissions()
    {
        $allPermissionIds = collect($this->permissionsByGroup)
            ->flatten(1)
            ->pluck('id');
            
        $this->selectAll = count($this->selectedPermissions) === $allPermissionIds->count();
    }

    public function save()
    {
        // Save logic would go here
       try {
        
        $this->validate([
            'name' => 'required|unique:roles,name',
            'selectedPermissions' => 'required|array|min:1',
        ]);

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->dispatch('alert', type: 'success', message: 'Role created successfully');
        $this->reset(['name', 'selectedPermissions', 'selectAll']);
           
       } catch (\Exception $e) {
           $this->dispatch('alert', type: 'error', message: $e->getMessage());
       }
    }
}; ?>

<div class="flex flex-col gap-6">
    <flux:heading size="xl">Create New Role</flux:heading>
    <flux:subheading>Define the role name and assign permissions to control access.</flux:subheading>

    <flux:separator variant="subtle" />

    <form wire:submit="save" class="flex flex-col gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 space-y-6">
            <flux:input wire:model="name" label="Role Name" placeholder="e.g. Store Manager" />

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <flux:heading size="lg">Permissions</flux:heading>
                    <flux:checkbox wire:model.live="selectAll" label="Select All Permissions" />
                </div>

                <div class="grid grid-cols-1 gap-6">
                    @foreach ($permissionsByGroup as $group => $permissions)
                        <div class="space-y-3 bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-700 p-4 rounded-xl">
                            <flux:heading size="base" class="font-medium text-zinc-900 dark:text-white">{{ $group }}</flux:heading>
                            <flux:separator variant="subtle" />
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach ($permissions as $permission)
                                    <flux:checkbox 
                                        wire:model.live="selectedPermissions" 
                                        value="{{ $permission['id'] }}" 
                                        label="{{ $permission['label'] }}" 
                                    />
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button variant="subtle" href="#">Cancel</flux:button>
            <flux:button variant="primary" type="submit">Create Role</flux:button>
        </div>
    </form>
</div>
