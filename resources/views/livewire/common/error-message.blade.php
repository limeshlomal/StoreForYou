<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public $show = false;
    public $message = '';
    public $heading = 'Error!';

    #[On('show-error')]
    public function showMessage($message, $heading = 'Error!')
    {
        $this->message = $message;
        $this->heading = $heading;
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
    }
}; ?>

<div>
    @if($show)
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => { show = false; $wire.close(); }, 7000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="mb-6 bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-red-500 rounded-full p-1 mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-red-900">{{ $heading }}</p>
                        <p class="text-sm text-red-700">{{ $message }}</p>
                    </div>
                </div>
                <button wire:click="close" class="text-red-600 hover:text-red-800 ml-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif
</div>
