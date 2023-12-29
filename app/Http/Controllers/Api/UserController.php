<?php

namespace App\Http\Controllers\Api;

use App\DTOs\User\CreateUserDTO;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function store(Request $request)
    {
        $user = $this->userService->create(
            CreateUserDTO::makeFromRequest($request)
        );

        if (!$user) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        return response()->json($user, Response::HTTP_CREATED);
    }
}
