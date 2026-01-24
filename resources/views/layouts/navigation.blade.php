<div x-data="{ open: true }" class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div :class="{'w-64': open, 'w-20': !open}" class="bg-gradient-to-b from-blue-600 to-blue-800 text-white transition-all duration-300 ease-in-out shadow-lg">
        <div class="flex flex-col h-full">
            <!-- Logo Section -->
            <div class="flex items-center justify-between p-6 border-b border-blue-700">
                <div :class="{'block': open, 'hidden': !open}" class="flex items-center space-x-3">
                    <div class="bg-white rounded-lg p-2">
                        <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold">Terakorp</span>
                    </div>
                </div>
                <button @click="open = !open" class="p-1.5 hover:bg-blue-700 rounded-lg transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="open ? 'M15 19l-7-7 7-7' : 'M9 5l7 7-7 7'"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-8 space-y-2">
                
                <!-- Dashboard (Semua User) -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }} transition">
                    <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 16l4-4m0 0l4 4m-4-4v4"></path>
                    </svg>
                    <span :class="{'block': open, 'hidden': !open}" class="font-medium">Dashboard</span>
                </a>

                <!-- ADMIN ONLY MENU -->
                @if(Auth::user()->isAdmin())
                    <div class="pt-4 mt-4 border-t border-blue-600">
                        <p :class="{'block': open, 'hidden': !open}" class="text-blue-300 text-xs font-semibold uppercase px-4 mb-3">Admin Functions</p>
                    </div>

                    <!-- Employee Management -->
                    <a href="{{ route('employee.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('employee.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }} transition">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM15 20H9m6 0h.01"></path>
                        </svg>
                        <span :class="{'block': open, 'hidden': !open}" class="font-medium">Manage Employees</span>
                    </a>

                    <!-- Upload Payroll -->
                    <a href="{{ route('payroll.upload') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('payroll.upload') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }} transition">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span :class="{'block': open, 'hidden': !open}" class="font-medium">Upload Payroll</span>
                    </a>
                @elseif(Auth::user()->isHR())

                    <!-- Employee Management -->
                    <a href="{{ route('employee.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('employee.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }} transition">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM15 20H9m6 0h.01"></path>
                        </svg>
                        <span :class="{'block': open, 'hidden': !open}" class="font-medium">Manage Employees</span>
                    </a>

                    <!-- Upload Payroll -->
                    <a href="{{ route('payroll.upload') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('payroll.upload') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }} transition">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span :class="{'block': open, 'hidden': !open}" class="font-medium">Upload Payroll</span>
                    </a>
                    <!-- Decrypt Files -->
                    <a href="{{ route('payroll.decrypt') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('payroll.decrypt') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }} transition">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span :class="{'block': open, 'hidden': !open}" class="font-medium">Decrypt Files</span>
                    </a>
                    <!-- My Payroll (User Personal) -->
                    <a href="{{ route('payroll.my-files') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('payroll.my-files') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }} transition">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <span :class="{'block': open, 'hidden': !open}" class="font-medium">My Files</span>
                    </a>
                @endif

                <!-- USER MENU (Decrypt & Personal Data) -->
                @if(Auth::user()->isUser() || Auth::user()->isAdmin())
                    <!-- Decrypt Files -->
                    <a href="{{ route('payroll.decrypt') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('payroll.decrypt') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }} transition">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span :class="{'block': open, 'hidden': !open}" class="font-medium">Decrypt Files</span>
                    </a>

                    <!-- My Payroll (User Personal) -->
                    <a href="{{ route('payroll.my-files') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('payroll.my-files') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }} transition">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <span :class="{'block': open, 'hidden': !open}" class="font-medium">My Files</span>
                    </a>
                @endif
            </nav>

            <!-- User Info & Logout -->
            <div class="border-t border-blue-700 p-4">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center font-bold flex-shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div :class="{'block': open, 'hidden': !open}">
                        <p class="font-medium text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-blue-200 text-xs truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2 text-blue-100 hover:bg-blue-700 rounded-lg transition text-left">
                        <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span :class="{'block': open, 'hidden': !open}" class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Page Content -->
        <main class="flex-1 overflow-auto bg-gray-50">
            {{ $slot }}
        </main>
    </div>
</div>