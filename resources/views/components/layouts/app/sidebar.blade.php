<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="main-sidebar border-e border-zinc-700 bg-zinc-900 dark">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Main')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
            
            <flux:navlist variant="outline">
                <flux:navlist.group expandable :expanded="request()->routeIs('products.*') || request()->routeIs('categories.*')" :heading="__('Products')" icon="shopping-bag">
                    <flux:navlist.item icon="list-bullet" :href="route('products.index')" :current="request()->routeIs('products.index')" wire:navigate>List</flux:navlist.item>
                    <flux:navlist.item icon="plus-circle" :href="route('products.create')" :current="request()->routeIs('products.create')" wire:navigate>Add</flux:navlist.item>
                    <flux:navlist.item icon="folder-plus" :href="route('categories.index')" :current="request()->routeIs('categories.index')" wire:navigate>Categories</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:navlist variant="outline">
                <flux:navlist.group expandable :expanded="request()->routeIs('users.*')" :heading="__('Users')" icon="shopping-bag">
                    <flux:navlist.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.index')" wire:navigate>Add</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:navlist variant="outline">
                <flux:navlist.group expandable :expanded="request()->routeIs('suppliers.*')" :heading="__('Suppliers')" icon="shopping-bag">
                    <flux:navlist.item icon="list-bullet" :href="route('suppliers.index')" :current="request()->routeIs('suppliers.index')" wire:navigate>Add</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:navlist variant="outline">
                <flux:navlist.group expandable :expanded="request()->routeIs('barcodes.*')" :heading="__('Barcode/Labels')" icon="shopping-bag">
                    <flux:navlist.item icon="list-bullet" :href="route('barcodes.index')" :current="request()->routeIs('barcodes.index')" wire:navigate>List</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:navlist variant="outline">
                <flux:navlist.group expandable :expanded="request()->routeIs('purchasings.*')" :heading="__('Purchasing')" icon="shopping-bag">
                    <flux:navlist.item icon="list-bullet" :href="route('purchasings.index')" :current="request()->routeIs('purchasings.index')" wire:navigate>List</flux:navlist.item>
                    <flux:navlist.item icon="plus-circle" :href="route('purchasings.create')" :current="request()->routeIs('purchasings.create')" wire:navigate>Add</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:navlist variant="outline">
                <flux:navlist.group expandable :expanded="request()->routeIs('invoices.*')" :heading="__('Invoices')" icon="shopping-bag">
                    <flux:navlist.item icon="plus-circle" :href="route('invoices.pos')" :current="request()->routeIs('invoices.pos')" wire:navigate>POS</flux:navlist.item>
                    <flux:navlist.item icon="list-bullet" :href="route('invoices.index')" :current="request()->routeIs('invoices.index')" wire:navigate>List</flux:navlist.item>
                    <flux:navlist.item icon="list-bullet" :href="route('invoices.hold')" :current="request()->routeIs('invoices.hold')" wire:navigate>Hold</flux:navlist.item>
                    <flux:navlist.item icon="list-bullet" :href="route('invoices.return')" :current="request()->routeIs('invoices.return')" wire:navigate>Return</flux:navlist.item>
                    <flux:navlist.item icon="list-bullet" :href="route('invoices.due')" :current="request()->routeIs('invoices.due')" wire:navigate>Due</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />


            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        <!-- Success Message Component - Global -->
        <div class="fixed top-4 right-4 z-50 max-w-md">
            @livewire('common.success-message')
        </div>

        <!-- Error Message Component - Global -->
        <div class="fixed top-20 right-4 z-50 max-w-md">
            @livewire('common.error-message')
        </div>

        @fluxScripts
    </body>
</html>
