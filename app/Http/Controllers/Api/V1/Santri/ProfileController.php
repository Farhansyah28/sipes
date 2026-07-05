<?php

namespace App\Http\Controllers\Api\V1\Santri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        // TODO: Return logged-in santri profile data
        return response()->json([
            'success' => true,
            'message' => 'Not implemented yet.',
            'data' => null
        ]);
    }
}
