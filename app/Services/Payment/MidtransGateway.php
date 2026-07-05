<?php

namespace App\Services\Payment;

class MidtransGateway implements PaymentGatewayInterface
{
    public function createInvoice(array $payload): array
    {
        // TODO: Call Midtrans SNAP API using CURL/Guzzle
        // Example logic:
        // $response = Http::withToken(config('payment.midtrans.server_key'))->post('...', $payload);
        
        return [
            'success' => true,
            'checkout_url' => 'https://app.midtrans.com/snap/v2/vtweb/dummy_token',
            'reference_id' => 'DUMMY-REF-123'
        ];
    }

    public function checkStatus(string $referenceId): array
    {
        // TODO: Implement Midtrans Check Status
        return [
            'success' => true,
            'status' => 'settlement'
        ];
    }

    public function handleWebhook(array $payload): bool
    {
        // TODO: Validate signature key and update database status
        return true;
    }
}
