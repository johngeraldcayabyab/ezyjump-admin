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

class MagpieCallback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $request;
    private $originalData;

    public function __construct($request, $originalData)
    {
        $this->request = $request;
        $this->originalData = $originalData;
    }


    public function handle(): void
    {
        $this->log('postback start');
        $request = $this->request;
        $this->log($this->originalData);
        $this->log($request);
        try {
            $domain = config('domain.wallet_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $response = $client->post('/eveningdew/deposits/postback', [
                'json' => $request
            ]);
            $responseJson = json_decode("$domain response : " . $response->getStatusCode(), true);
            $this->log($responseJson);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->log($message);
        }
        $this->log('postback end');
    }

    private function log($message)
    {
        Log::channel('wallet')->info($message);
    }
}
