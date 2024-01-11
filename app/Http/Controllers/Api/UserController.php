<?php

namespace App\Http\Controllers\Api;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ConfirmEmailRequest;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\UserRegisterRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Models\User;
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

    public function confirmEmail(ConfirmEmailRequest $request)
    {
        if ($this->userService->confirmEmail($request->get('confirmation_token'), $request->user()->id)) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        return response()->json(null, Response::HTTP_NOT_FOUND);
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

    public function logout(Request $request)
    {
        if($this->userService->logout($request)) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        return response()->json(null, Response::HTTP_NOT_FOUND);
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        $user = $this->userService->update(UpdateUserDTO::makeFromRequest($request), $id);

        if (!$user) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        return response()->json($user, Response::HTTP_OK);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        if($this->userService->forgotPassword($request->get('email'))) {
            return response()->json(['message' => __('A password reset link has been sent to your email.')], Response::HTTP_OK);
        }

        return response()->json(['message' => __('Invalid email, are you sure it is correct and verified?')], Response::HTTP_NOT_FOUND);
    }

    public function resetPassword(ResetPasswordRequest $request, string $id)
    {
        if($this->userService->resetPassword($request->get('password'), $request->get('token'), $id)) {
            return response()->json(['message' => __('Password changed successfully.')], Response::HTTP_OK);
        }

        return response()->json(['message' => __('Invalid data, are you sure it is correct and verified?')], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
