<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dashboard Simple untuk semua user
    public function index()
    {
        // Basic stats untuk user
        $totalPayrolls = Payroll::count();
        $avgWaktuEnkripsi = Payroll::avg('waktu_enkripsi');
        $totalSizeAsli = Payroll::sum('ukuran_asli');
        $maxWaktuEnkripsi = Payroll::max('waktu_enkripsi');
        $minWaktuEnkripsi = Payroll::min('waktu_enkripsi');

        // Chart Data
        $payrolls = Payroll::orderBy('created_at')->get();
        $chartLabels = $payrolls->map(fn($p) => $p->employee_name . ' (' . round($p->ukuran_asli / 1024 / 1024, 2) . 'MB)')->toArray();
        $chartData = $payrolls->pluck('waktu_enkripsi')->toArray();

        // Data Table
        $dataTable = Payroll::orderBy('created_at', 'desc')->get();

        return view('dashboard', compact(
            'totalPayrolls', 'avgWaktuEnkripsi', 'totalSizeAsli', 
            'maxWaktuEnkripsi', 'minWaktuEnkripsi',
            'chartLabels', 'chartData', 'dataTable'
        ));
    }

    // Dashboard Analytics lengkap untuk ADMIN ONLY
    public function analytics()
    {
        // Summary Statistics
        $totalPayrolls = Payroll::count();
        $totalSizeAsli = Payroll::sum('ukuran_asli');
        $totalSizeEnkripsi = Payroll::sum('ukuran_enkripsi');
        $avgWaktuEnkripsi = Payroll::avg('waktu_enkripsi');
        $maxWaktuEnkripsi = Payroll::max('waktu_enkripsi');
        $minWaktuEnkripsi = Payroll::min('waktu_enkripsi');

        // Overhead calculation
        $overheadBytes = $totalSizeEnkripsi - $totalSizeAsli;
        $overheadPercent = ($totalSizeAsli > 0) ? ($overheadBytes / $totalSizeAsli) * 100 : 0;

        // Chart Data
        $payrolls = Payroll::orderBy('created_at')->get();
        $chartLabels = $payrolls->map(fn($p) => $p->employee_name . ' (' . round($p->ukuran_asli / 1024 / 1024, 2) . 'MB)')->toArray();
        $chartData = $payrolls->pluck('waktu_enkripsi')->toArray();
        $chartSizeData = $payrolls->pluck('ukuran_asli')->map(fn($s) => $s / 1024 / 1024)->toArray();

        // Size Categories
        $sizeCategories = [
            'Small (<500KB)' => Payroll::where('ukuran_asli', '<', 500000)->count(),
            'Medium (500KB-2MB)' => Payroll::whereBetween('ukuran_asli', [500000, 2000000])->count(),
            'Large (>2MB)' => Payroll::where('ukuran_asli', '>', 2000000)->count(),
        ];

        // Data Table
        $dataTable = Payroll::orderBy('created_at', 'desc')->get();

        return view('admin.analytics', compact(
            'totalPayrolls', 'totalSizeAsli', 'totalSizeEnkripsi', 'avgWaktuEnkripsi',
            'maxWaktuEnkripsi', 'minWaktuEnkripsi', 'overheadBytes', 'overheadPercent',
            'chartLabels', 'chartData', 'chartSizeData', 
            'sizeCategories', 'dataTable'
        ));
    }

    public function exportCsv()
    {
        $payrolls = Payroll::all();

        $csv = "Employee Name,Telegram ID,Ukuran Asli (KB),Ukuran Enkripsi (KB),Waktu Enkripsi (ms),Overhead (%),Created At\n";

        foreach ($payrolls as $payroll) {
            $overhead = $payroll->ukuran_asli > 0 ? (($payroll->ukuran_enkripsi - $payroll->ukuran_asli) / $payroll->ukuran_asli) * 100 : 0;
            $csv .= "\"{$payroll->employee_name}\",\"{$payroll->telegram_id}\"," 
                . round($payroll->ukuran_asli / 1024, 2) . "," 
                . round($payroll->ukuran_enkripsi / 1024, 2) . "," 
                . round($payroll->waktu_enkripsi, 4) . "," 
                . round($overhead, 2) . "," 
                . $payroll->created_at->format('Y-m-d H:i:s') . "\n";
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'payroll_encryption_data_' . now()->format('Y-m-d_His') . '.csv');
    }
}