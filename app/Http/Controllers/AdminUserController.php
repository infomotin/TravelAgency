<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:security.view')->only(['index', 'show']);
        $this->middleware('permission:security.update')->only(['edit', 'update', 'editPassword', 'updatePassword']);
    }

    public function index()
    {
        $users = User::with('agency')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user);
    }

    public function editPassword(User $user)
    {
        return view('admin.users.password', compact('user'));
    }

    public function updatePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.show', $user);
    }
}

