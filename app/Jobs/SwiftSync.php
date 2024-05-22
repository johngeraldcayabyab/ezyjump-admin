<?php

namespace App\Jobs;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SwiftSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    private $token;
    private $bearerToken;

    public function __construct($id, $token, $bearerToken)
    {
        $this->id = $id;
        $this->token = $token;
        $this->bearerToken = $bearerToken;
    }

    public function handle(): void
    {
        $id = $this->id;
        $token = $this->token;
        $bearerToken = $this->bearerToken;
        $this->log("swift sync id " . $id);
        $this->log("swift sync token " . $token);
        $data = [
            'data' => [
                $id,
            ]
        ];
        $this->log($data);
        try {
            $domain = config('domain.gateway_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $response = $client->put('/api/orders/sync', [
                'headers' => [
                    'Authorization' => $bearerToken,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            $syncStatus = $response->getStatusCode();
            $this->log("swift sync status $id $syncStatus");
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->log("swift sync error id: $id $message");
        }
    }

    private function log($message)
    {
        Log::channel('gateway')->info($message);
    }
}
