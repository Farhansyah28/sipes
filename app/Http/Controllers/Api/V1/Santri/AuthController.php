<?php

namespace App\Http\Controllers\Api\V1\Santri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // TODO: Implement Santri login logic (verify NIS/Phone, generate Sanctum token)
        return response()->json([
            'success' => true,
            'message' => 'Not implemented yet.',
            'data' => null
        ]);
    }

    public function logout(Request $request)
    {
        // TODO: Implement Revoke Token
        return response()->json([
            'success' => true,
            'message' => 'Not implemented yet.',
            'data' => null
        ]);
    }
}
