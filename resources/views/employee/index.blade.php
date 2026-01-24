<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Employee Management</h2>
                @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                <a href="{{ route('employee.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    Add Employee
                </a>
                @endif
            </div>

            @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Name</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Telegram ID</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Department</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Position</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Email</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($employees as $employee)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $employee->name }}</td>
                                    <td class="px-4 py-3 text-gray-600"><code class="bg-gray-100 px-2 py-1 rounded">{{ $employee->telegram_id }}</code></td>
                                    <td class="px-4 py-3 text-gray-600">{{ $employee->email }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $employee->department }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $employee->position }}</td>
                                    <td class="px-4 py-3 text-sm space-x-2">
                                        <a href="{{ route('employee.edit', $employee) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Edit</a>

                                        @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                                        <form action="{{ route('employee.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('Hapus karyawan ini? Semua link ke akun akan dilepas.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Delete</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-500">No employees yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>