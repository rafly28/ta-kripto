<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dashboard Simple untuk semua user
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'hr') {
            // HR: Semua payroll
            $totalPayrolls = Payroll::count();
            $totalUserFiles = Payroll::count();
            $dataTable = Payroll::with('employee')->orderBy('created_at', 'desc')->limit(5)->get();
        } else {
            // USER: Payroll milik sendiri (via employee)
            $employee = Employee::where('user_id', $user->id)->first();
            if ($employee) {
                $totalPayrolls = Payroll::where('employee_id', $employee->id)->count();
                $totalUserFiles = $totalPayrolls;
                $dataTable = Payroll::with('employee')
                    ->where('employee_id', $employee->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            } else {
                $totalPayrolls = 0;
                $totalUserFiles = 0;
                $dataTable = collect();
            }
        }

        // Total employees
        $totalEmployees = Employee::count();

        return view('dashboard', compact(
            'totalPayrolls', 'totalUserFiles', 'totalEmployees', 'dataTable'
        ));
    }

    // Dashboard Analytics lengkap untuk HR
    public function analytics()
    {
        $totalPayrolls = Payroll::count();
        $totalSizeAsli = Payroll::sum('ukuran_asli') ?? 0;
        $totalSizeEnkripsi = Payroll::sum('ukuran_enkripsi') ?? 0;
        $avgWaktuEnkripsi = Payroll::avg('waktu_enkripsi') ?? 0;
        $maxWaktuEnkripsi = Payroll::max('waktu_enkripsi') ?? 0;
        $minWaktuEnkripsi = Payroll::min('waktu_enkripsi') ?? 0;

        // Overhead calculation
        $overheadBytes = $totalSizeEnkripsi - $totalSizeAsli;
        $overheadPercent = ($totalSizeAsli > 0) ? ($overheadBytes / $totalSizeAsli) * 100 : 0;

        // Chart Data
        $payrolls = Payroll::with('employee')->orderBy('created_at')->get();
        $chartLabels = $payrolls->map(fn($p) => $p->employee->name ?? '')->toArray();

        return view('dashboard-analytics', compact(
            'totalPayrolls',
            'totalSizeAsli',
            'totalSizeEnkripsi',
            'avgWaktuEnkripsi',
            'maxWaktuEnkripsi',
            'minWaktuEnkripsi',
            'overheadBytes',
            'overheadPercent',
            'chartLabels',
            'payrolls'
        ));
    }

    public function exportCsv()
    {
        $payrolls = Payroll::with('employee')->get();

        $csvHeader = [
            'ID', 'Employee Name', 'Periode', 'Bulan', 'File', 'Ukuran Asli', 'Ukuran Enkripsi', 'Waktu Enkripsi', 'Created At'
        ];

        $rows = [];
        foreach ($payrolls as $p) {
            $rows[] = [
                $p->id,
                $p->employee->name ?? '',
                $p->periode,
                $p->bulan,
                $p->encrypted_file_path,
                $p->ukuran_asli,
                $p->ukuran_enkripsi,
                $p->waktu_enkripsi,
                $p->created_at,
            ];
        }

        $filename = 'payrolls_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://memory', 'r+');
        fputcsv($handle, $csvHeader);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}