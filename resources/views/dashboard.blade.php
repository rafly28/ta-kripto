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
                        @if(Auth::user()->isAdmin())
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
                        @if(Auth::user()->isAdmin())
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



            <!-- Recent Files Table -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    @if(Auth::user()->isAdmin())
                        Files I Uploaded
                    @else
                        My Files
                    @endif
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Employee Name</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">File Size</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Encryption Time</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($dataTable ?? [] as $payroll)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $payroll->employee_name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ round($payroll->ukuran_asli / 1024 / 1024, 2) }} MB</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ round($payroll->waktu_enkripsi, 2) }} ms
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $payroll->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">No files yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>