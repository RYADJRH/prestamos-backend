<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogoutMobileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();
        $remove_token = $request->user()->tokens()->where('id', $token->id)->delete();
        return new JsonResponse(['success' => (bool)$remove_token]);
    }
}
