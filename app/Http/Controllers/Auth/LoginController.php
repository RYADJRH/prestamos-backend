<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $remeberMe      = $request->input('remember_me', false);
        $credentials    = $request->only('nick_name_user', 'password');
        if (Auth::attempt($credentials, $remeberMe)) {
            return new JsonResponse(['success' => true, 'user' => auth()->user()]);
        }
        return new JsonResponse(['success' => false, 'message' => __('auth.failed')], 422);
    }
}
