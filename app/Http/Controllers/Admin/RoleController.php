<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        $roles = EmployeeRole::withCount('employees')->latest()->paginate(20);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        $permissionGroups = config('permissions.groups', []);

        return view('admin.roles.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'is_active' => 'boolean',
        ]);

        $allValidKeys = $this->getAllPermissionKeys();
        $permissions = array_values(array_intersect($validated['permissions'] ?? [], $allValidKeys));

        EmployeeRole::create([
            'name' => $validated['name'],
            'slug' => EmployeeRole::generateUniqueSlug($validated['name']),
            'description' => $validated['description'] ?? null,
            'permissions' => $permissions,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', __('messages.role_created_successfully'));
    }

    public function edit(EmployeeRole $role)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        $permissionGroups = config('permissions.groups', []);

        return view('admin.roles.edit', compact('role', 'permissionGroups'));
    }

    public function update(Request $request, EmployeeRole $role)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'is_active' => 'boolean',
        ]);

        $allValidKeys = $this->getAllPermissionKeys();
        $permissions = array_values(array_intersect($validated['permissions'] ?? [], $allValidKeys));

        $role->update([
            'name' => $validated['name'],
            'slug' => EmployeeRole::generateUniqueSlug($validated['name'], $role->id),
            'description' => $validated['description'] ?? null,
            'permissions' => $permissions,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', __('messages.role_updated_successfully'));
    }

    public function destroy(EmployeeRole $role)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, __('messages.access_denied'));
        }

        if ($role->employees()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', __('messages.role_has_employees'));
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', __('messages.role_deleted_successfully'));
    }

    /**
     * Get all valid permission keys from config (excluding admin_only groups).
     */
    private function getAllPermissionKeys(): array
    {
        $keys = [];
        $groups = config('permissions.groups', []);

        foreach ($groups as $group) {
            if (!empty($group['admin_only'])) {
                continue;
            }
            foreach ($group['permissions'] as $key => $label) {
                $keys[] = $key;
            }
        }

        return $keys;
    }
}
