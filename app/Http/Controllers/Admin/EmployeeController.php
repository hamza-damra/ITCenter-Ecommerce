<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        $employees = User::where('role', 'employee')
            ->with('employeeRole')
            ->latest()
            ->paginate(20);

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        $roles = EmployeeRole::where('is_active', true)->get();

        return view('admin.employees.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'employee_role_id' => 'required|exists:employee_roles,id',
            'status' => 'required|in:active,inactive',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->password = Hash::make($validated['password']);
        $user->role = 'employee';
        $user->status = $validated['status'];
        $user->employee_role_id = $validated['employee_role_id'];
        $user->save();

        return redirect()->route('admin.employees.index')
            ->with('success', __('messages.employee_created_successfully'));
    }

    public function edit(User $employee)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        if ($employee->role !== 'employee') {
            abort(404);
        }

        $roles = EmployeeRole::where('is_active', true)->get();

        return view('admin.employees.edit', compact('employee', 'roles'));
    }

    public function update(Request $request, User $employee)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        if ($employee->role !== 'employee') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'employee_role_id' => 'required|exists:employee_roles,id',
            'status' => 'required|in:active,inactive',
        ]);

        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        $employee->phone = $validated['phone'] ?? null;
        $employee->employee_role_id = $validated['employee_role_id'];
        $employee->status = $validated['status'];

        if (!empty($validated['password'])) {
            $employee->password = Hash::make($validated['password']);
        }

        $employee->save();

        return redirect()->route('admin.employees.index')
            ->with('success', __('messages.employee_updated_successfully'));
    }

    public function destroy(User $employee)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        if ($employee->role !== 'employee') {
            abort(404);
        }

        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', __('messages.employee_deleted_successfully'));
    }

    /**
     * Toggle employee active/inactive status.
     */
    public function toggleStatus(User $employee)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        if ($employee->role !== 'employee') {
            abort(404);
        }

        $employee->status = $employee->status === 'active' ? 'inactive' : 'active';
        $employee->save();

        return redirect()->route('admin.employees.index')
            ->with('success', __('messages.employee_status_updated'));
    }
}
