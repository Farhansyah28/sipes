<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// use App\Services\WhatsApp\WhatsAppGatewayInterface;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;
    public $message;
    
    // Retry attempts
    public $tries = 3;
    
    // Backoff logic in seconds (retry after 1 min, 5 min, 10 min)
    public $backoff = [60, 300, 600];

    /**
     * Create a new job instance.
     */
    public function __construct(string $phone, string $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Example: Resolve gateway and send message
        // $waGateway = app()->make(WhatsAppGatewayInterface::class);
        // $waGateway->sendMessage($this->phone, $this->message);
        
        \Log::info("Job SendWhatsAppMessage processed. To: {$this->phone}, Msg: {$this->message}");
    }
}
