<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * User registration
     *
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function registration(RegistrationRequest $request): JsonResponse
    {
        $password = Hash::make($request->password);
        User::query()->create(['password' => $password] + $request->validated());

        return response()->json([
            'success' => true,
        ], 201);
    }

    /**
     * User login
     *
     * @param AuthRequest $request
     * @return array|JsonResponse
     */
    public function auth(AuthRequest $request): array|JsonResponse
    {
        if(Auth::attempt($request->validated())) {
            return [
                'success' => true,
                'token' => $request->user()->createToken('api')->plainTextToken,
            ];
        }

        return response()->json([
            'success' => false,
            'errors' => [
                'email' => ['Incorrect login data'],
            ],
        ], 422);
    }

    /**
     * User logout
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
