<?php

namespace App\Jobs;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SwiftCallback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $referenceId;
    private $bearerToken;

    public function __construct($referenceId, $bearerToken)
    {
        $this->referenceId = $referenceId;
        $this->bearerToken = $bearerToken;
    }

    public function handle(): void
    {
        $bearerToken = $this->bearerToken;
        $referenceId = $this->referenceId;
        $data = ['data' => $referenceId];
        $this->log($bearerToken);
        $this->log("retry callback ref no " . $referenceId);
        try {
            $domain = config('domain.gateway_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $response = $client->put('/api/callbacks/retry?status=SUCCESS', [
                'headers' => [
                    'Authorization' => $bearerToken,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            $retryStatus = $response->getStatusCode();
            $this->log("callback retry status " . $referenceId . " " . $retryStatus);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->log($message);
        }
    }

    private function log($message)
    {
        Log::channel('gateway')->info($message);
    }
}
