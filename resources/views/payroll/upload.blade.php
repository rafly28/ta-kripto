<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Upload Form Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-2xl font-bold mb-2 text-gray-800">Upload Payroll File</h2>
                        <p class="text-gray-600 text-sm mb-6">Encrypt your payroll files using AES-256 encryption</p>
                        
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('payroll.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            
                            <!-- Employee Name -->
                            <div>
                                <label for="employee_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Employee Name
                                </label>
                                <input 
                                    type="text" 
                                    id="employee_name"
                                    name="employee_name" 
                                    placeholder="Enter employee name" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                    required
                                >
                                @error('employee_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Telegram ID -->
                            <div>
                                <label for="telegram_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Telegram ID
                                </label>
                                <input 
                                    type="text" 
                                    id="telegram_id"
                                    name="telegram_id" 
                                    placeholder="Enter Telegram ID" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                    required
                                >
                                @error('telegram_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div>
                                <label for="file_slip" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Select Payroll File
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <input 
                                        type="file" 
                                        id="file_slip"
                                        name="file_slip" 
                                        class="hidden" 
                                        required
                                        onchange="document.getElementById('file-name').textContent = this.files[0].name"
                                    >
                                    <label for="file_slip" class="cursor-pointer">
                                        <p class="text-gray-600 font-medium">Click to upload or drag and drop</p>
                                        <p class="text-sm text-gray-500">PDF, Excel, or Document files</p>
                                    </label>
                                    <p id="file-name" class="text-sm text-blue-600 mt-2"></p>
                                </div>
                                @error('file_slip')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button 
                                    type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <span>Encrypt & Upload</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Card Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 h-fit sticky top-20">
                        <h3 class="font-semibold text-blue-900 mb-4 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Security Information</span>
                        </h3>
                        <ul class="text-sm text-blue-800 space-y-3">
                            <li class="flex items-start space-x-2">
                                <span class="text-blue-600 font-bold mt-0.5">✓</span>
                                <span>AES-256 encryption for maximum security</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-blue-600 font-bold mt-0.5">✓</span>
                                <span>Decrypt password sent to employee Telegram</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>