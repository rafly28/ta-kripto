<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
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
            'email' => 'required|email|unique:employees',
            'telegram_id' => 'required|string|max:50|unique:employees',
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
        ]);

        $employee = Employee::create($validated);

        // Link or create User account for employee
        $user = User::where('email', $validated['email'])->first();
        if (! $user) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt('password'), // minta ubah password lewat profile
                'role' => 'employee',
                'telegram_id' => $validated['telegram_id'] ?? null,
                'email_verified_at' => now(),
            ]);
        } else {
            // ensure telegram_id present
            if (empty($user->telegram_id) && ! empty($validated['telegram_id'])) {
                $user->update(['telegram_id' => $validated['telegram_id']]);
            }
        }

        $employee->user_id = $user->id;
        $employee->save();

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
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'telegram_id' => 'required|string|max:50|unique:employees,telegram_id,' . $employee->id,
            'department' => 'required|string|max:100',
            'position' => 'required|string|max:100',
        ]);

        $employee->update($validated);

        // Sync user record
        $existingUser = User::where('email', $validated['email'])->first();

        if ($existingUser) {
            // attach existing user
            $employee->user_id = $existingUser->id;
            // update telegram if needed
            if (empty($existingUser->telegram_id) && ! empty($validated['telegram_id'])) {
                $existingUser->update(['telegram_id' => $validated['telegram_id']]);
            }
            $employee->save();
        } else {
            // if employee already linked to a user, update that user's email
            if ($employee->user_id) {
                $user = User::find($employee->user_id);
                if ($user) {
                    $user->update([
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'telegram_id' => $validated['telegram_id'] ?? $user->telegram_id,
                    ]);
                }
            } else {
                // create new user
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => bcrypt('password'),
                    'role' => 'employee',
                    'telegram_id' => $validated['telegram_id'] ?? null,
                    'email_verified_at' => now(),
                ]);
                $employee->user_id = $user->id;
                $employee->save();
            }
        }

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
