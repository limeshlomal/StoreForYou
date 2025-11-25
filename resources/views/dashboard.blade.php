<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Products -->
            <div class="relative overflow-hidden bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-sm border border-zinc-200 dark:border-zinc-700 group hover:shadow-md transition-all">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Products</div>
                        <div class="text-3xl font-bold text-zinc-900 dark:text-white mt-2">1,240</div>
                        <div class="flex items-center gap-1 mt-2 text-xs font-medium text-green-600 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-3">
                                <path fill-rule="evenodd" d="M12 7c1.104 0 2 .896 2 2 0 1.104-.896 2-2 2a2 2 0 1 1 0-4zm0 10c1.104 0 2 .896 2 2 0 1.104-.896 2-2 2a2 2 0 1 1 0-4z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M10 3a1 1 0 0 1 .707.293l3 3a1 1 0 0 1-1.414 1.414L10 5.414 7.707 7.707a1 1 0 0 1-1.414-1.414l3-3A1 1 0 0 1 10 3zm-3.707 9.293a1 1 0 0 1 1.414 0L10 14.586l2.293-2.293a1 1 0 0 1 1.414 1.414l-3 3a1 1 0 0 1-1.414 0l-3-3a1 1 0 0 1 0-1.414z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M12 13a1 1 0 100 2 1 1 0 000-2zm-2.293-3.293a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 11.414l-3.707 3.707a1 1 0 01-1.414-1.414l4-4z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                            </svg>
                            <span>+12% from last month</span>
                        </div>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Monthly Sales -->
            <div class="relative overflow-hidden bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-sm border border-zinc-200 dark:border-zinc-700 group hover:shadow-md transition-all">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Monthly Sales</div>
                        <div class="text-3xl font-bold text-zinc-900 dark:text-white mt-2">Rs. 450k</div>
                        <div class="flex items-center gap-1 mt-2 text-xs font-medium text-green-600 dark:text-green-400">
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-3">
                                <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                            </svg>
                            <span>+8.2% from last month</span>
                        </div>
                    </div>
                    <div class="p-3 bg-green-50 dark:bg-green-900/30 rounded-xl text-green-600 dark:text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="relative overflow-hidden bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-sm border border-zinc-200 dark:border-zinc-700 group hover:shadow-md transition-all">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-red-500/20 to-orange-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative flex justify-between items-start">
                    <div>
                        <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Low Stock Alerts</div>
                        <div class="text-3xl font-bold text-zinc-900 dark:text-white mt-2">5</div>
                        <div class="flex items-center gap-1 mt-2 text-xs font-medium text-red-600 dark:text-red-400">
                            <span>Requires attention</span>
                        </div>
                    </div>
                    <div class="p-3 bg-red-50 dark:bg-red-900/30 rounded-xl text-red-600 dark:text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-1">
            <!-- Sales Chart Section -->
            <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Sales Overview</h3>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 text-xs font-medium rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">Weekly</button>
                        <button class="px-3 py-1 text-xs font-medium rounded-full bg-black text-white dark:bg-white dark:text-black">Monthly</button>
                    </div>
                </div>
                
                <!-- Chart Placeholder -->
                <div class="flex-1 flex items-end justify-between gap-3 h-64 px-2 pb-4">
                    <!-- Fake Bars with Gradient -->
                    @foreach([40, 65, 50, 85, 60, 75, 90, 55, 70, 45, 80, 95] as $height)
                    <div class="w-full bg-zinc-100 dark:bg-zinc-800 rounded-t-lg h-full relative group overflow-hidden">
                        <div class="absolute bottom-0 w-full bg-gradient-to-t from-zinc-900 to-zinc-600 dark:from-white dark:to-zinc-400 rounded-t-lg transition-all duration-500 group-hover:opacity-80" style="height: {{ $height }}%"></div>
                        <!-- Tooltip -->
                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-black text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                            Rs. {{ $height * 1000 }}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-2 text-xs text-zinc-400 px-1 font-medium">
                    <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span><span>Jul</span><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
                </div>
            </div>

            <!-- Product Overview (Pie Chart) -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 flex flex-col">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-6">Product Categories</h3>
                
                <div class="flex-1 flex flex-col items-center justify-center relative">
                    <!-- CSS Pie Chart -->
                    <div class="relative size-48 rounded-full" 
                         style="background: conic-gradient(
                            #18181b 0% 35%, 
                            #52525b 35% 60%, 
                            #a1a1aa 60% 85%, 
                            #e4e4e7 85% 100%
                         );">
                         <!-- Inner Circle for Donut Effect -->
                         <div class="absolute inset-0 m-auto size-32 bg-white dark:bg-zinc-900 rounded-full flex items-center justify-center flex-col">
                             <span class="text-3xl font-bold text-zinc-900 dark:text-white">1.2k</span>
                             <span class="text-xs text-zinc-500">Total Items</span>
                         </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="size-3 rounded-full bg-zinc-900"></div>
                            <span class="text-zinc-600 dark:text-zinc-400">Men's Wear</span>
                        </div>
                        <span class="font-bold text-zinc-900 dark:text-white">35%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="size-3 rounded-full bg-zinc-600"></div>
                            <span class="text-zinc-600 dark:text-zinc-400">Women's Wear</span>
                        </div>
                        <span class="font-bold text-zinc-900 dark:text-white">25%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="size-3 rounded-full bg-zinc-400"></div>
                            <span class="text-zinc-600 dark:text-zinc-400">Kids</span>
                        </div>
                        <span class="font-bold text-zinc-900 dark:text-white">25%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <div class="size-3 rounded-full bg-zinc-200"></div>
                            <span class="text-zinc-600 dark:text-zinc-400">Accessories</span>
                        </div>
                        <span class="font-bold text-zinc-900 dark:text-white">15%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Low Stock Combined -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
             <!-- Low Stock Alerts Table -->
             <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Low Stock Alerts</h3>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-zinc-100 dark:border-zinc-800">
                                <th class="pb-3 font-medium text-zinc-500">Product</th>
                                <th class="pb-3 font-medium text-zinc-500 text-right">Stock</th>
                                <th class="pb-3 font-medium text-zinc-500 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            <tr>
                                <td class="py-3 font-medium text-zinc-900 dark:text-white">Cotton Shorts</td>
                                <td class="py-3 text-right text-zinc-600 dark:text-zinc-400">12</td>
                                <td class="py-3 text-right">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400">Low</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 font-medium text-zinc-900 dark:text-white">Denim Jeans</td>
                                <td class="py-3 text-right text-zinc-600 dark:text-zinc-400">5</td>
                                <td class="py-3 text-right">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400">Critical</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 font-medium text-zinc-900 dark:text-white">Summer Hat</td>
                                <td class="py-3 text-right text-zinc-600 dark:text-zinc-400">2</td>
                                <td class="py-3 text-right">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400">Critical</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Recent Transactions</h3>
                    <a href="#" class="text-sm text-zinc-500 hover:text-zinc-700 font-medium">View All</a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-zinc-900 dark:text-white">Order #1024</div>
                                <div class="text-xs text-zinc-500">2 mins ago</div>
                            </div>
                        </div>
                        <div class="text-sm font-bold text-zinc-900 dark:text-white">+ Rs. 4,500</div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-zinc-900 dark:text-white">Order #1023</div>
                                <div class="text-xs text-zinc-500">15 mins ago</div>
                            </div>
                        </div>
                        <div class="text-sm font-bold text-zinc-900 dark:text-white">+ Rs. 1,200</div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-zinc-900 dark:text-white">Order #1022</div>
                                <div class="text-xs text-zinc-500">1 hour ago</div>
                            </div>
                        </div>
                        <div class="text-sm font-bold text-zinc-900 dark:text-white">+ Rs. 8,900</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
