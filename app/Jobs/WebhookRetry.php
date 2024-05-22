<?php

namespace App\Jobs;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WebhookRetry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $token;
    private $id;
    private $path;

    public function __construct($token, $id, $path)
    {
        $this->token = $token;
        $this->id = $id;
        $this->path = $path;
    }

    public function handle(): void
    {
        $token = $this->token;
        $id = $this->id;
        $path = $this->path;
        $data = ['data' => [$id]];

        $this->log($token);
        $this->log("Wallet webhook retry callback id: " . $id);
        $bearerToken = "Bearer $token";
        try {
            $domain = config('domain.wallet_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $response = $client->patch($path, [
                'headers' => [
                    'Authorization' => $bearerToken,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            $retryStatus = $response->getStatusCode();
            if ($retryStatus === 200) {
                Cache::put("webhook_$id", $id, now()->addMinutes(5));
            }
            $this->log("Wallet webhook callback retry status " . $id . " " . $retryStatus);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->log($message);
        }
    }

    private function log($message)
    {
        Log::channel('wallet')->info($message);
    }
}
