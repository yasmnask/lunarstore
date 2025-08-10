<?php

namespace App\Services\Client;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getUser(?string $username = null): User|null
    {
        if ($username) {
            $user_data = DB::table('users')
                ->where('username', $username)
                ->first();
            return (new User)->newFromBuilder((array) $user_data);
        } else {
            // If no username is provided, return the authenticated user
            return Auth::guard('web')->user();
        }

        return null;
    }
}
