<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\PaymentGatewayInterface;

class WebhookController extends Controller
{
    public function payment(Request $request, $provider)
    {
        // Resolve gateway from container based on provider parameter
        // For now, it's just a skeleton
        
        // $gateway = app()->make(PaymentGatewayInterface::class); // Usually dynamically resolved
        // $success = $gateway->handleWebhook($request->all());

        return response()->json([
            'success' => true,
            'message' => "Webhook received for provider: {$provider}",
            'data' => null
        ]);
    }
}
