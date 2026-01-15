<script src="https://cdn.tailwindcss.com"></script>

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4 text-indigo-700">Upload Slip Gaji (Enkripsi AES-256)</h2>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('payroll.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" name="employee_name" placeholder="Nama Karyawan" class="border p-2 rounded w-full" required>
                        <input type="text" name="telegram_id" placeholder="Telegram ID" class="border p-2 rounded w-full" required>
                        <input type="file" name="file_slip" class="border p-1 rounded w-full" required>
                    </div>
                    <button type="submit" class="mt-4 bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                        Enkripsi & Simpan
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold mb-4">Data Pengujian Kriptografi</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">Karyawan</th>
                            <th class="border p-2">Status</th>
                            <th class="border p-2">Waktu Enkrip</th>
                            <th class="border p-2">Ukuran (Asli -> Enkrip)</th>
                            <th class="border p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Payroll::latest()->get() as $p)
                        <tr>
                            <td class="border p-2">{{ $p->employee_name }}</td>
                            <td class="border p-2 text-green-600 font-bold">Terkirim via Telegram</td>
                            <td class="border p-2">{{ number_format($p->waktu_enkripsi, 4) }} s</td>
                            <td class="border p-2">{{ round($p->ukuran_asli / 1024, 2) }} KB -> {{ round($p->ukuran_enkripsi / 1024, 2) }} KB</td>
                            <td class="border p-2">
                                <a href="{{ route('payroll.download', $p->id) }}" class="text-blue-600 underline">Download .enc</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>