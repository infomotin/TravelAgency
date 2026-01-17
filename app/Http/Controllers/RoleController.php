<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:security.view')->only(['index', 'editPermissions', 'editUsers']);
        $this->middleware('permission:security.create')->only(['create', 'store']);
        $this->middleware('permission:security.update')->only(['edit', 'update', 'updatePermissions', 'updateUsers']);
        $this->middleware('permission:security.delete')->only(['destroy']);
    }

    public function index()
    {
        $roles = Role::withCount('users')->orderBy('name')->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:50', 'unique:roles,slug'],
        ]);
        $validated['agency_id'] = app('currentAgency')->id;
        Role::create($validated);

        return redirect()->route('roles.index');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $role->update($validated);

        return redirect()->route('roles.index');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index');
    }

    public function editPermissions(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions()->pluck('permissions.id')->all();

        return view('roles.permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $permissionIds = $request->input('permissions', []);
        $role->permissions()->sync($permissionIds);

        return redirect()->route('roles.index');
    }

    public function editUsers(Role $role)
    {
        $users = User::where('agency_id', app('currentAgency')->id)->orderBy('name')->get();
        $roleUsers = $role->users()->pluck('users.id')->all();

        return view('roles.users', compact('role', 'users', 'roleUsers'));
    }

    public function updateUsers(Request $request, Role $role)
    {
        $userIds = $request->input('users', []);
        $role->users()->sync($userIds);

        return redirect()->route('roles.index');
    }
}
