<?php

namespace App\Services\Client;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Attempt to log in using Auth::attempt
     *
     * @param  array  $credentials  ['email' => ..., 'password' => ..., 'remember' => true/false]
     * @return bool
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(array $credentials): bool
    {
        $remember = $credentials['remember'] ?? false;

        unset($credentials['remember']);

        $user = DB::table('users')
            ->where('email', $credentials['email'])
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages(['Invalid email or password. Please try again.']);
        }

        // check if user is active
        if (!$user->is_active) {
            throw ValidationException::withMessages(['Your account is inactive. Please contact administrator.']);
        }

        if (!$remember) {
            // update token
            DB::table('users')
                ->where('id', $user->id)
                ->update(['remember_token' => null]);
        }

        // Cast stdClass ke User model
        $user = (new User)->newFromBuilder((array) $user);

        Auth::login($user, $remember);

        return true;
    }

    /**
     * Attempt to register a new user using Auth::register
     *
     * @param  array  $data  ['email' => ..., 'password' => ..., 'name' => ...]
     * @return bool
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(array $data): bool
    {
        // check terms and conditions
        if (!array_key_exists('terms', $data) || !$data['terms']) {
            throw ValidationException::withMessages(['You must accept the terms and conditions!']);
        }

        // create new user
        try {
            DB::beginTransaction();

            $new_user = DB::table('users')->insertGetId([
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!$new_user) {
                throw ValidationException::withMessages(['Failed to create your account. Please try again later.']);
            }

            Auth::loginUsingId($new_user, true);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return true;
    }
}
