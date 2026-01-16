<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h2 class="text-2xl font-bold text-gray-900 mb-6">📊 Data Analytics</h2>

            <!-- KPI Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <!-- Total Files -->
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
                    <p class="text-gray-600 text-xs font-medium uppercase">Total Files</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPayrolls ?? 0 }}</p>
                </div>

                <!-- Avg Encryption Time -->
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
                    <p class="text-gray-600 text-xs font-medium uppercase">Avg Encryption Time</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ round($avgWaktuEnkripsi ?? 0, 2) }}<span class="text-sm font-normal"> ms</span></p>
                </div>

                <!-- Total Data Size -->
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
                    <p class="text-gray-600 text-xs font-medium uppercase">Total Size</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ round(($totalSizeAsli ?? 0) / 1024 / 1024, 2) }}<span class="text-sm font-normal"> MB</span></p>
                </div>

                <!-- Min Encryption Time -->
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-500">
                    <p class="text-gray-600 text-xs font-medium uppercase">Min Time</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ round($minWaktuEnkripsi ?? 0, 2) }}<span class="text-sm font-normal"> ms</span></p>
                </div>

                <!-- Max Encryption Time -->
                <div class="bg-white rounded-lg shadow p-5 border-l-4 border-red-500">
                    <p class="text-gray-600 text-xs font-medium uppercase">Max Time</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ round($maxWaktuEnkripsi ?? 0, 2) }}<span class="text-sm font-normal"> ms</span></p>
                </div>
            </div>

            <!-- Encryption Overhead Analysis -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🔐 Encryption Overhead Analysis</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-gray-600 text-sm mb-1">Total Original Size</p>
                        <p class="text-2xl font-bold text-blue-600">{{ round(($totalSizeAsli ?? 0) / 1024 / 1024, 2) }} MB</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-gray-600 text-sm mb-1">Total Encrypted Size</p>
                        <p class="text-2xl font-bold text-green-600">{{ round(($totalSizeEnkripsi ?? 0) / 1024 / 1024, 2) }} MB</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-gray-600 text-sm mb-1">Overhead ({{ round($overheadPercent ?? 0, 2) }}%)</p>
                        <p class="text-2xl font-bold text-purple-600">{{ round(($overheadBytes ?? 0) / 1024, 2) }} KB</p>
                    </div>
                </div>
                <div class="mt-4 p-4 bg-blue-50 rounded border-l-4 border-blue-600">
                    <p class="text-sm text-blue-900">
                        <strong>Analisis:</strong> Overhead sebesar {{ round($overheadPercent ?? 0, 2) }}% terdiri dari HMAC (32 bytes) + IV (16 bytes) + PKCS7 Padding. Overhead ini normal untuk enkripsi AES-256-CBC.
                    </p>
                </div>
            </div>

            <!-- Encryption Time Chart -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">⏱️ Encryption Time Analysis</h3>
                <canvas id="encryptionChart" style="max-height: 300px;"></canvas>
            </div>

            <!-- Data Table with Export -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">📑 Complete Encryption Data</h3>
                    <a href="{{ route('dashboard.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                        📥 Export CSV
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Employee</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Original Size</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Encrypted Size</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Encryption Time</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Overhead %</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($dataTable ?? [] as $payroll)
                                @php
                                    $overhead = $payroll->ukuran_asli > 0 ? (($payroll->ukuran_enkripsi - $payroll->ukuran_asli) / $payroll->ukuran_asli) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $payroll->employee_name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ round($payroll->ukuran_asli / 1024 / 1024, 2) }} MB</td>
                                    <td class="px-4 py-3 text-gray-600">{{ round($payroll->ukuran_enkripsi / 1024 / 1024, 2) }} MB</td>
                                    <td class="px-4 py-3 text-gray-600"><strong>{{ round($payroll->waktu_enkripsi, 2) }}</strong> ms</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ round($overhead, 2) }}%
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $payroll->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada data payroll</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        @if(isset($chartLabels) && count($chartLabels) > 0)
        const ctx = document.getElementById('encryptionChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Encryption Time (ms)',
                    data: {!! json_encode($chartData ?? []) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3b82f6',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        title: { display: true, text: 'Time (ms)' }
                    }
                }
            }
        });
        @endif
    </script>
</x-app-layout>