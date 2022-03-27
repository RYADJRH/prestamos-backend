<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials    = $request->only('nick_name_user', 'password');
        if (Auth::attempt($credentials)) {
            return new JsonResponse(['user' => auth()->user()]);
        }
        return new JsonResponse(['message' => '', 'errors' => ['nick_name_user' => [__('auth.failed')]]], 422);
    }
}
