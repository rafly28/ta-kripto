<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Decrypt Form Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-2xl font-bold mb-2 text-gray-800">Decrypt Payroll File</h2>
                        <p class="text-gray-600 text-sm mb-6">Upload your encrypted .enc file and enter the decryption password to access the original file</p>
                        
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

                        @if($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $errors->first() }}
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('payroll.decrypt.process') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            
                            <!-- File Upload -->
                            <div>
                                <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Select Encrypted File (.enc)
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-500 transition">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <input 
                                        type="file" 
                                        id="file"
                                        name="file" 
                                        class="hidden" 
                                        accept=".enc"
                                        required
                                        onchange="document.getElementById('file-name').textContent = this.files[0].name"
                                    >
                                    <label for="file" class="cursor-pointer">
                                        <p class="text-gray-600 font-medium">Click to upload or drag and drop</p>
                                        <p class="text-sm text-gray-500">.enc encrypted files only</p>
                                    </label>
                                    <p id="file-name" class="text-sm text-green-600 mt-2"></p>
                                </div>
                                @error('file')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Decryption Password
                                </label>
                                <input 
                                    type="password" 
                                    id="password"
                                    name="password" 
                                    placeholder="Enter your decryption password" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                                    required
                                >
                                @error('password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button 
                                    type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center space-x-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span>Decrypt File</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Card Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 h-fit sticky top-20">
                        <h3 class="font-semibold text-green-900 mb-4 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Decryption Guide</span>
                        </h3>
                        <ul class="text-sm text-green-800 space-y-3">
                            <li class="flex items-start space-x-2">
                                <span class="text-green-600 font-bold mt-0.5">1</span>
                                <span>Upload your encrypted .enc file</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-green-600 font-bold mt-0.5">2</span>
                                <span>Enter the correct decryption password</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-green-600 font-bold mt-0.5">3</span>
                                <span>Click "Decrypt File" to recover original content</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-green-600 font-bold mt-0.5">⚠</span>
                                <span class="text-xs">Keep your password secure and never share it</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>