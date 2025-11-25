<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.pos')]
class extends Component {
    //
}; ?>


<div class="w-full h-full bg-gray-100 overflow-hidden">
    <div class="flex h-full w-full p-3 gap-3">

        <!-- LEFT SIDE (2/3) -->
        <div class="flex flex-col w-1/2 bg-white rounded-xl shadow-lg p-4">

            <!-- Title -->
            <div class="text-xl font-semibold mb-3">
                Sales Invoice #{{ $invoiceNumber ?? '00000' }}
            </div>

            <!-- Category + Search -->
            <div class="flex gap-3 mb-3">
                <select class="border p-2 rounded w-48">
                    <option>Retail</option>
                </select>

                <input type="text" placeholder="Product Name"
                       class="border p-2 rounded w-full" />
            </div>

            <!-- CART TABLE -->
            <div class="border rounded-lg overflow-hidden flex-1">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="p-2 w-10">#</th>
                            <th class="p-2">Name</th>
                            <th class="p-2 w-20">Qty</th>
                            <th class="p-2 w-28">Unit Price</th>
                            <th class="p-2 w-24">Discount</th>
                            <th class="p-2 w-28">Total</th>
                            <th class="p-2 w-10"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="border-b">
                            <td class="p-2">1</td>
                            <td class="p-2">Sample Item</td>
                            <td class="p-2">
                                <input type="number" class="border w-16 p-1 rounded" value="2" />
                            </td>
                            <td class="p-2">
                                <input type="text" class="border w-24 p-1 rounded" value="2300" />
                            </td>
                            <td class="p-2">
                                <input type="text" class="border w-16 p-1 rounded" value="0" />
                            </td>
                            <td class="p-2">4600</td>
                            <td class="p-2 text-red-600 font-bold cursor-pointer">×</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- TOTALS SECTION -->
            <div class="mt-auto border-t pt-4">
                <div class="space-y-3">
                    
                    <!-- Subtotal / Discount Row -->
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Discount (%)</label>
                            <input type="number" 
                                   class="w-full border-gray-300 rounded-xl shadow-sm focus:border-zinc-500 focus:ring-zinc-500 text-right font-bold text-lg p-3" 
                                   placeholder="0" />
                        </div>
                        <div class="flex-1 text-right">
                            <div class="text-sm text-gray-500 mb-1">Subtotal</div>
                            <div class="text-2xl font-bold text-gray-900">{{ number_format(4600, 2) }}</div>
                        </div>
                    </div>

                    <!-- Payment Method Row -->
                    <div class="flex gap-4">
                        <div class="w-1/3">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Payment</label>
                            <select class="w-full border-gray-300 rounded-xl shadow-sm focus:border-zinc-500 focus:ring-zinc-500 text-lg p-3 font-medium">
                                <option>Cash</option>
                                <option>Card</option>
                                <option>Transfer</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Amount</label>
                            <input type="text" 
                                   class="w-full border-gray-300 rounded-xl shadow-sm focus:border-zinc-500 focus:ring-zinc-500 text-right font-bold text-xl p-3" 
                                   placeholder="0.00" 
                                   wire:keydown.enter="processPayment" /> <!-- Added Enter trigger suggestion -->
                        </div>
                    </div>

                    <!-- Grand Total & Change -->
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="bg-zinc-900 text-white p-5 rounded-2xl shadow-lg">
                            <div class="flex justify-between items-start mb-1">
                                <div class="text-zinc-400 text-xs uppercase tracking-wider font-medium">Total Payable</div>
                                <div class="bg-zinc-800 text-zinc-300 text-xs px-2 py-1 rounded-md font-bold">3 Items</div>
                            </div>
                            <div class="text-3xl font-bold tracking-tight">{{ number_format(4600, 2) }}</div>
                        </div>
                        <div class="bg-gray-100 text-gray-800 p-5 rounded-2xl shadow-inner border border-gray-200">
                            <div class="text-gray-500 text-xs uppercase tracking-wider font-medium">Change</div>
                            <div class="text-3xl font-bold tracking-tight">0.00</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4">
                        <button class="w-full py-3 px-4 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                            Hold / New Order (F3)
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- RIGHT SIDE (1/3) -->
        <div class="w-1/2 bg-white rounded-xl shadow-lg flex flex-col p-4 overflow-hidden">
            <div class="text-xl font-semibold mb-4 text-zinc-800">Product List</div>

            <!-- Search -->
            <div class="relative mb-4">
                <input type="text"
                       placeholder="Search Items..."
                       class="w-full border-gray-300 rounded-xl shadow-sm focus:border-zinc-500 focus:ring-zinc-500 p-3 pl-10" />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- PRODUCT GRID -->
            <div class="grid grid-cols-3 gap-3 overflow-y-auto pr-1 pb-2">

                <!-- Example product card -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-zinc-300 transition-all cursor-pointer p-3 flex flex-col gap-2 group">
                    <div class="flex justify-between items-start">
                        <div class="text-xs bg-zinc-100 text-zinc-500 px-2 py-1 rounded-md font-medium group-hover:bg-zinc-200 transition-colors">Qty: 24</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-zinc-900">Rs. 2,300.00</div>
                        <div class="text-sm font-medium text-zinc-600 leading-tight mt-1 group-hover:text-zinc-900 transition-colors">JANEESHA SHIRT LS</div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-zinc-300 transition-all cursor-pointer p-3 flex flex-col gap-2 group">
                    <div class="flex justify-between items-start">
                        <div class="text-xs bg-zinc-100 text-zinc-500 px-2 py-1 rounded-md font-medium group-hover:bg-zinc-200 transition-colors">Qty: 8</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-zinc-900">Rs. 1,800.00</div>
                        <div class="text-sm font-medium text-zinc-600 leading-tight mt-1 group-hover:text-zinc-900 transition-colors">DEMIGODS T-SHIRT</div>
                    </div>
                </div>

                 <!-- More placeholders to show grid -->
                 <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-zinc-300 transition-all cursor-pointer p-3 flex flex-col gap-2 group">
                    <div class="flex justify-between items-start">
                        <div class="text-xs bg-zinc-100 text-zinc-500 px-2 py-1 rounded-md font-medium group-hover:bg-zinc-200 transition-colors">Qty: 12</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-zinc-900">Rs. 1,200.00</div>
                        <div class="text-sm font-medium text-zinc-600 leading-tight mt-1 group-hover:text-zinc-900 transition-colors">COTTON SHORTS</div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-zinc-300 transition-all cursor-pointer p-3 flex flex-col gap-2 group">
                    <div class="flex justify-between items-start">
                        <div class="text-xs bg-zinc-100 text-zinc-500 px-2 py-1 rounded-md font-medium group-hover:bg-zinc-200 transition-colors">Qty: 50</div>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-zinc-900">Rs. 3,500.00</div>
                        <div class="text-sm font-medium text-zinc-600 leading-tight mt-1 group-hover:text-zinc-900 transition-colors">DENIM JEANS</div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
