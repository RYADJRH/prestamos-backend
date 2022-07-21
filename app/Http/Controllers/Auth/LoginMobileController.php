<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginMobileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials    = $request->only('nick_name_user', 'password');
        $user = User::where('nick_name_user', $credentials['nick_name_user'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password_user)) {
            return new JsonResponse(['message' => '', 'errors' => ['nick_name_user' => [__('auth.failed')]]], 422);
        }

        $token =  $user->createToken($user->id_user);
        $duration = config('sanctum.expiration');

        return new JsonResponse([
            'access_token' => $token->plainTextToken,
            'expired_at' => $token->accessToken->created_at->addMinutes($duration)->timestamp,
            'token_type' => 'Bearer',
        ]);
    }
}
