<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
    /**
     * Create a new invoice or payment link
     */
    public function createInvoice(array $payload): array;

    /**
     * Check status of a transaction
     */
    public function checkStatus(string $referenceId): array;

    /**
     * Handle incoming webhook payload
     */
    public function handleWebhook(array $payload): bool;
}
