<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $this->ensureRolesExist();

            $roleName = User::query()->count() === 0 ? 'admin' : 'client';

            $role = Role::query()->where('name', $roleName)->firstOrFail();

            User::query()->create([
                'role_id' => $role->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        });

        return back()->with('success', 'Account created successfully. You can now log in.');
    }

    private function ensureRolesExist(): void
    {
        Role::query()->firstOrCreate(
            ['name' => 'admin']
        );

        Role::query()->firstOrCreate(
            ['name' => 'client']
        );
    }
}
