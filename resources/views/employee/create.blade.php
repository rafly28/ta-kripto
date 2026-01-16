<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h2 class="text-2xl font-bold text-gray-900 mb-6">➕ Add New Employee</h2>

            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('employee.store') }}" method="POST">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Employee Name *</label>
                        <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="John Doe" value="{{ old('name') }}">
                        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Telegram ID -->
                    <div class="mb-4">
                        <label for="telegram_id" class="block text-sm font-semibold text-gray-900 mb-2">Telegram ID *</label>
                        <input type="text" name="telegram_id" id="telegram_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="123456789" value="{{ old('telegram_id') }}">
                        <p class="text-gray-500 text-xs mt-1">Get from @userinfobot on Telegram</p>
                        @error('telegram_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Department -->
                    <div class="mb-4">
                        <label for="department" class="block text-sm font-semibold text-gray-900 mb-2">Department *</label>
                        <input type="text" name="department" id="department" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Finance" value="{{ old('department') }}">
                        @error('department') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Position -->
                    <div class="mb-6">
                        <label for="position" class="block text-sm font-semibold text-gray-900 mb-2">Position *</label>
                        <input type="text" name="position" id="position" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Manager" value="{{ old('position') }}">
                        @error('position') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                            ✅ Save Employee
                        </button>
                        <a href="{{ route('employee.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-900 px-6 py-2 rounded-lg font-semibold transition">
                            ❌ Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>