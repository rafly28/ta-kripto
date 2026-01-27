<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Welcome Section -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 text-white">
                    <h1 class="text-2xl font-bold mb-1">Welcome, {{ Auth::user()->name }}!</h1>
                    <p class="text-blue-100 text-sm">Secure File Encryption System</p>
                </div>
            </div>

            <!-- KPI Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Total Files -->
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
                    <p class="text-gray-600 text-xs font-medium uppercase">
                        @if(Auth::user()->isHr())
                            Files Uploaded
                        @else
                            Files Assigned
                        @endif
                    </p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPayrolls ?? 0 }}</p>
                </div>

                <!-- Total Employees / Total Files in System -->
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
                    <p class="text-gray-600 text-xs font-medium uppercase">
                        @if(Auth::user()->isHr())
                            Total Employees
                        @else
                            Employees
                        @endif
                    </p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalEmployees ?? 0 }}</p>
                </div>

                <!-- Total Files in System (Admin only) -->
                @if(Auth::user()->isAdmin())
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
                    <p class="text-gray-600 text-xs font-medium uppercase">Total in System</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUserFiles ?? 0 }}</p>
                </div>
                @else
                <!-- Available Actions (User) -->
                <!-- <div class="bg-white rounded-lg shadow p-5 border-l-4 border-indigo-500">
                    <p class="text-gray-600 text-xs font-medium uppercase">Status</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">Active</p>
                </div> -->
                @endif
            </div>
        </div>
    </div>
</x-app-layout>