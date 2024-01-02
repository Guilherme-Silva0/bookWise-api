<?php

namespace App\Http\Controllers\Api;

use App\DTOs\User\CreateUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function store(UserRegisterRequest $request)
    {
        $user = $this->userService->create(
            CreateUserDTO::makeFromRequest($request)
        );

        if (!$user) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token], Response::HTTP_CREATED);
    }

    public function me(Request $request)
    {
        $user = $this->userService->me($request);

        if (!$user) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        return response()->json($user, Response::HTTP_OK);
        
    }

    public function login(Request $request)
    {
        $user = $this->userService->login($request);

        if (!$user) {
            return response()->json(['message' => __('auth.failed')], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token], Response::HTTP_OK);
    }
}
