<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 text-white">
                    <h1 class="text-2xl font-bold mb-1">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="text-blue-100 text-sm">Secure employee payroll files efficiently</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Stat Card 1 -->
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Total Uploads</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">12</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Files Secured</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">48</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-2">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-white rounded-lg shadow p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Last Activity</p>
                            <p class="text-lg font-bold text-gray-800 mt-1">2 hours ago</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-2">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow p-5">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Recent Activity</h3>
                <div class="space-y-3">
                    <div class="flex items-center pb-3 border-b border-gray-200 last:border-b-0">
                        <div class="bg-blue-100 rounded-full p-2 mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">Payroll file uploaded</p>
                            <p class="text-xs text-gray-500">December 15, 2025</p>
                        </div>
                    </div>
                    <div class="flex items-center pb-3 border-b border-gray-200 last:border-b-0">
                        <div class="bg-green-100 rounded-full p-2 mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1V3a1 1 0 011-1h5a1 1 0 011 1v1h1V3a1 1 0 011 1v1h1a2 2 0 012 2v2h1a1 1 0 110 2h-1v6h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-1v1a1 1 0 11-2 0v-1h-5v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h1V3a1 1 0 010-2h5V2z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">File decrypted successfully</p>
                            <p class="text-xs text-gray-500">December 12, 2025</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-purple-100 rounded-full p-2 mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800">Profile updated</p>
                            <p class="text-xs text-gray-500">December 10, 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>