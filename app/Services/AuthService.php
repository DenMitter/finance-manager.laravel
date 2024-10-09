<?php
namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;

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
}
