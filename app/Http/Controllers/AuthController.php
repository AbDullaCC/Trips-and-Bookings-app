<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function __construct(AuthService $service)
    {
        parent::__construct($service, UserResource::class);
    }

    public function register(CreateUserRequest $request)
    {
        $result = $this->service->createUser($request->validated());

        $result['user'] = $this->resource::make($result['user']);

        return $this->success('user created successfully', $result, 201);
    }

    public function login(LoginUserRequest $request)
    {
        $result = [];

        $result = $this->service->UserLogin($request->only('phone', 'password'));

        $result['user'] = $this->resource::make($result['user']);

        return $this->success('user logged in successfully', $result);
    }
}
