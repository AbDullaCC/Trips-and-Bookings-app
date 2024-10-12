<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function createUser($data)
    {
        $user = User::create($data);
        $user->assignRole('user');

        $result = [];
        $result['user'] = $user;

        $token = $user->createToken("user_token")->plainTextToken;
        $result['token'] = $token;

        return $result;
    }

    public function UserLogin($data){
        if (!Auth::attempt($data)){
            throw new CustomException('invalid credentials',['credentials' => 'incorrect phone or password'], 401);
        }
        $user = User::query()->where('phone', '=', $data['phone'])->first();

        $token = $user->createToken("user_token")->plainTextToken;

        $result = [];
        $result['user'] = $user;
        $result['token'] = $token;

        return $result;
    }
}
