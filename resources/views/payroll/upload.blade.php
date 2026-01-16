<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h2 class="text-2xl font-bold text-gray-900 mb-6">📤 Upload Payroll File</h2>

            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('payroll.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Employee Dropdown -->
                    <div class="mb-4">
                        <label for="employee_id" class="block text-sm font-semibold text-gray-900 mb-2">Select Employee *</label>
                        <select name="employee_id" id="employee_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="loadEmployeeInfo()">
                            <option value="">-- Choose Employee --</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" data-telegram="{{ $emp->telegram_id }}" data-department="{{ $emp->department }}" data-position="{{ $emp->position }}">
                                {{ $emp->name }} ({{ $emp->department }})
                            </option>
                            @endforeach
                        </select>
                        @error('employee_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Employee Info (Auto-filled) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-gray-600 text-xs font-semibold uppercase">Telegram ID</p>
                            <p id="telegram_info" class="text-lg font-bold text-gray-900 mt-1">-</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-semibold uppercase">Department</p>
                            <p id="department_info" class="text-lg font-bold text-gray-900 mt-1">-</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-semibold uppercase">Position</p>
                            <p id="position_info" class="text-lg font-bold text-gray-900 mt-1">-</p>
                        </div>
                    </div>

                    <!-- File Input -->
                    <div class="mb-6">
                        <label for="file" class="block text-sm font-semibold text-gray-900 mb-2">Upload File *</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition cursor-pointer" onclick="document.getElementById('file').click()">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-8-12l-4-4m0 0L20 8m4-4v12m12 0v12"></path>
                            </svg>
                            <p class="text-gray-600 font-semibold">Click to upload or drag and drop</p>
                            <p class="text-gray-500 text-xs">PDF, DOC, XLS up to 10MB</p>
                            <input type="file" name="file" id="file" required class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx">
                        </div>
                        <p id="file_name" class="text-sm text-gray-600 mt-2"></p>
                        @error('file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                            ✅ Upload
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 px-6 py-2 rounded-lg font-semibold transition text-center">
                            ❌ Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function loadEmployeeInfo() {
            const select = document.getElementById('employee_id');
            const selected = select.options[select.selectedIndex];
            
            document.getElementById('telegram_info').textContent = selected.dataset.telegram || '-';
            document.getElementById('department_info').textContent = selected.dataset.department || '-';
            document.getElementById('position_info').textContent = selected.dataset.position || '-';
        }

        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '';
            document.getElementById('file_name').textContent = fileName ? '📄 ' + fileName : '';
        });
    </script>
</x-app-layout>