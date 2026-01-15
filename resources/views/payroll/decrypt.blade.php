<script src="https://cdn.tailwindcss.com"></script>

<x-app-layout>
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center">
                <h2 class="text-xl font-bold mb-4">Dekripsi Slip Gaji</h2>
                <p class="text-sm text-gray-600 mb-6">Unggah file .enc Anda dan masukkan password untuk melihat file asli.</p>

                <form action="{{ route('payroll.decrypt.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file_enc" class="border p-2 w-full mb-4" required>
                    <input type="password" name="password" placeholder="Masukkan Password Dekripsi" class="border p-2 w-full mb-4" required>
                    
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded w-full hover:bg-green-700 font-bold">
                        Buktikan Dekripsi
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>