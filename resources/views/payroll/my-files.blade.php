<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h2 class="text-2xl font-bold text-gray-900 mb-6">My Payroll Files</h2>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">File Name</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">File Size</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Uploaded At</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($payrolls as $payroll)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-900 font-medium">
                                        {{ basename($payroll->encrypted_file_path) }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ round($payroll->ukuran_asli / 1024 / 1024, 2) }} MB
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-xs">
                                        {{ $payroll->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm space-x-2">
                                        <a href="{{ route('payroll.download', $payroll->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                        No files assigned yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>