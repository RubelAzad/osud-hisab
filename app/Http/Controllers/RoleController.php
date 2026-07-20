<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('users')->where('pharmacy_id', currentPharmacyId())->orderBy('name')->get();

        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        return view('roles.create', ['permissions' => $this->groupedPermissions()]);
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->validated('name'), 'pharmacy_id' => currentPharmacyId()]);
        $role->syncPermissions($request->validated('permissions') ?? []);

        return redirect()->route('roles.index')->with('success', 'Role created.');
    }

    public function edit(Role $role): View
    {
        abort_unless($role->pharmacy_id === currentPharmacyId(), 404);

        return view('roles.edit', [
            'role' => $role,
            'permissions' => $this->groupedPermissions(),
            'rolePermissions' => $role->permissions->pluck('name')->toArray(),
        ]);
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        abort_unless($role->pharmacy_id === currentPharmacyId(), 404);

        $role->update(['name' => $request->validated('name')]);
        $role->syncPermissions($request->validated('permissions') ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_unless($role->pharmacy_id === currentPharmacyId(), 404);

        if ($role->users()->exists()) {
            return back()->with('error', 'Cannot delete a role that has users assigned.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted.');
    }

    private function groupedPermissions()
    {
        return Permission::orderBy('name')->get()->groupBy(fn ($permission) => explode('.', $permission->name)[0]);
    }
}
