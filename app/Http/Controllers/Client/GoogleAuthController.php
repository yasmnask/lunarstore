<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->email)->first();
            $redirectTo = null;

            if ($user) {
                if (!$user->google_id) {
                    $dataUpdate = [
                        'google_id' => $googleUser->id,
                    ];
                    if ($googleUser->avatar) {
                        $dataUpdate['avatar'] = $googleUser->avatar;
                    }
                    $user->update($dataUpdate);
                }

                // check is user is active
                if (!$user->is_active) {
                    session()->flash('swal-auth', [
                        'title' => 'Account Inactive',
                        'icon' => 'warning',
                        'text' => 'Your account is inactive. Please contact support.',
                    ]);
                    return redirect('/login');
                }

                Auth::guard('web')->login($user, true);
                $redirectTo = ($user->username != '_g_') ? '/' : '/profile/_g_?tab=personal';
            } elseif (User::where('google_id', $googleUser->id)->exists()) {
                session()->flash('swal-auth', [
                    'title' => 'Account Already Linked',
                    'icon' => 'warning',
                    'text' => 'This Google account is already linked to another user. Please use a different account.',
                ]);
                return redirect('/login');
            } else {
                $newUser = User::create([
                    'username' => '_g_',
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'is_active' => true,
                    'password' => Hash::make(env('DEFAULT_PASSWORD')),
                ]);

                Auth::guard('web')->login($newUser, true);
                $redirectTo = '/profile/_g_?tab=personal';
                // session()->flash('google-auth', [
                //     'title' => 'Account Linked',
                //     'icon' => 'warning',
                //     'text' => 'Your account has been linked with Google. Please fill in your username and change your password.',
                // ]);
            }
            session()->flash('swal-auth', [
                'title' => 'Successfully Logged In',
                'icon' => 'success',
                'text' => 'Login successful! Welcome to Lunar Store.',
            ]);

            return redirect()->intended($redirectTo);
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Google authentication failed: ' . $e->getMessage());
        }
    }
}
