<?php

namespace App\Services\Admin;

use App\Models\UserAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Attempt to log in using Auth::attempt
     *
     * @param  array  $credentials  ['username' => ..., 'password' => ..., 'remember' => true/false]
     * @return bool
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(array $credentials): bool
    {
        $remember = $credentials['remember'] ?? false;

        unset($credentials['remember']);

        $admin = DB::table('user_admins')
            ->where('username', $credentials['username'])
            ->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            throw ValidationException::withMessages(['Invalid username or password. Please try again.']);
        }

        if (!$remember) {
            // update token
            DB::table('user_admins')
                ->where('id', $admin->id)
                ->update(['remember_token' => null]);
        }

        // Cast stdClass ke User model
        $admin = (new UserAdmin)->newFromBuilder((array) $admin);

        Auth::guard('admin')->login($admin, $remember);

        return true;
    }
}
