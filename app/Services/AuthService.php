<?php

namespace App\Services;

use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService {
    public static function register($data): string
    {
        $user = new User();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        $user->save();

        return $user->createToken('token')->plainTextToken;
    }

    /**
     * @throws Exception
     */
    public static function login($credentials): string
    {
        $user = User::query()->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new Exception('Invalid credentials');
        }

        return $user->createToken('token')->plainTextToken;
    }

    /**
     * @throws Exception
     */
    public static function logout(): void
    {
        $user = auth()->user();
        if ($user) {
            $user->tokens()->delete();
        } else {
            throw new Exception('User is not authenticated');
        }
    }

    /**
     * @throws Exception
     */
    public static function resetPassword($data): void
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new Exception('Failed to reset password');
        }
    }

    /**
     * @throws Exception
     */
    public static function sendResetLinkEmail($data): void
    {
        $status = Password::sendResetLink($data);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new Exception('Failed to send reset link');
        }
    }

    public function updatePassword($data): \Illuminate\Http\JsonResponse
    {
        $status = Password::reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token' => $data['token']
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['success' => true, 'message' => __($status)]);
        } else {
            return response()->json(['success' => false, 'message' => __($status)], 400);
        }
    }
}
