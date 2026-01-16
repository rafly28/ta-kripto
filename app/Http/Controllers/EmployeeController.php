<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // List semua karyawan
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('employee.index', compact('employees'));
    }

    // Form create karyawan
    public function create()
    {
        return view('employee.create');
    }

    // Store karyawan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:employees',
            'telegram_id' => 'required|string|max:50|unique:employees',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
        ]);

        Employee::create($validated);

        return redirect()->route('employee.index')->with('success', 'Employee added successfully!');
    }

    // Form edit karyawan
    public function edit(Employee $employee)
    {
        return view('employee.edit', compact('employee'));
    }

    // Update karyawan
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:employees,name,' . $employee->id,
            'telegram_id' => 'required|string|max:50|unique:employees,telegram_id,' . $employee->id,
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
        ]);

        $employee->update($validated);

        return redirect()->route('employee.index')->with('success', 'Employee updated successfully!');
    }

    // Delete karyawan
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employee.index')->with('success', 'Employee deleted successfully!');
    }

    // API endpoint untuk get telegram_id by employee name (untuk upload form)
    public function getTelegramId($name)
    {
        $employee = Employee::where('name', $name)->first();
        
        if ($employee) {
            return response()->json([
                'telegram_id' => $employee->telegram_id,
                'department' => $employee->department,
                'position' => $employee->position,
            ]);
        }

        return response()->json(['error' => 'Employee not found'], 404);
    }
}
