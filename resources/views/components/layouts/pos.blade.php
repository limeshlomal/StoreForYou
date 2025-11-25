<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="h-screen bg-gray-100 dark:bg-zinc-800 flex flex-col overflow-hidden">
        <!-- POS Header -->
        <header class="h-16 bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between px-6 shrink-0 z-10 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="font-bold text-xl text-zinc-900 dark:text-white tracking-tight">Amaro Fashion</span>
                <div class="h-6 w-px bg-gray-300 dark:bg-zinc-700 mx-2"></div>
                <span class="font-medium text-lg text-zinc-500 dark:text-zinc-400 tracking-tight">POS Terminal</span>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800" wire:navigate>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Dashboard
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 2.062-2.062a.99.99 0 0 0 0-1.414L15.75 12" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-hidden relative">
            {{ $slot }}
        </main>

        <!-- Global Messages -->
        <div class="fixed top-4 right-4 z-50 max-w-md">
            @livewire('common.success-message')
        </div>
        <div class="fixed top-20 right-4 z-50 max-w-md">
            @livewire('common.error-message')
        </div>

        @fluxScripts
    </body>
</html>
