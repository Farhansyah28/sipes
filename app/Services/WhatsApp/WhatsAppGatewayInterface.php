<?php

namespace App\Services\WhatsApp;

interface WhatsAppGatewayInterface
{
    /**
     * Send a text message to a WhatsApp number
     *
     * @param string $to Phone number (e.g. 62812345678)
     * @param string $message Text message
     * @return array
     */
    public function sendMessage(string $to, string $message): array;

    /**
     * Send a document/file to a WhatsApp number
     *
     * @param string $to Phone number (e.g. 62812345678)
     * @param string $fileUrl Public URL of the file
     * @param string $caption Caption for the document
     * @return array
     */
    public function sendDocument(string $to, string $fileUrl, string $caption = ''): array;
}
