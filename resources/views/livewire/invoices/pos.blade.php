<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>


<div class="w-full h-screen bg-gray-100 overflow-hidden">
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

            <!-- TOTALS -->
            <div class="grid grid-cols-4 gap-4 mt-4">

                <!-- Discount -->
                <div class="bg-blue-600 text-white p-4 rounded-xl text-center">
                    <div class="font-semibold">Discount %</div>
                    <input type="text"
                           class="w-full p-1 mt-2 rounded text-black border" />
                </div>

                <!-- Total -->
                <div class="bg-green-600 text-white p-4 rounded-xl flex flex-col justify-center text-center">
                    <div>Total</div>
                    <div class="text-2xl font-bold">{{ number_format(4600, 2) }}</div>
                </div>

                <!-- Payment Box -->
                <div class="col-span-2 bg-white p-4 rounded-xl shadow text-center">
                    <select class="border p-2 rounded w-32">
                        <option>Cash</option>
                    </select>

                    <input type="text"
                           placeholder="Amount"
                           class="border p-2 rounded w-32 ml-2" />
                </div>
            </div>

            <!-- HOLD BUTTON -->
            <div class="mt-4">
                <button class="w-full bg-pink-600 text-white p-4 rounded-xl text-lg font-bold">
                    HOLD / NEW (F3)
                </button>
            </div>
        </div>

        <!-- RIGHT SIDE (1/3) -->
        <div class="w-1/2 bg-white rounded-xl shadow-lg flex flex-col p-4 overflow-hidden">
            <div class="text-xl font-semibold mb-3">Product List</div>

            <!-- Search -->
            <input type="text"
                   placeholder="Search Items"
                   class="border p-2 rounded mb-3 w-full" />

            <!-- PRODUCT GRID -->
            <div class="grid grid-cols-4 gap-4 overflow-y-auto pr-1">

                <!-- Example product card -->
                <div class="bg-green-300 hover:bg-green-400 p-4 rounded-xl cursor-pointer text-center">
                    <div class="text-xs text-red-800 font-bold">Qty: 24 In</div>
                    <div class="text-lg font-semibold">Rs. 2,300.00</div>
                    <div class="mt-1 font-medium text-sm">JANEESHA SHIRT LS</div>
                </div>

                <div class="bg-green-300 hover:bg-green-400 p-4 rounded-xl text-center">
                    <div class="text-xs text-red-800 font-bold">Qty: 8 In</div>
                    <div class="text-lg font-semibold">Rs. 1,800.00</div>
                    <div class="mt-1 font-medium text-sm">DEMIGODS T-SHIRT</div>
                </div>

            </div>
        </div>

    </div>
</div>
