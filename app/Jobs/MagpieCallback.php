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

    public function __construct($request)
    {
        $this->request = $request;
    }


    public function handle(): void
    {
        Log::channel('wallet')->info('postback start');
        $request = $this->request;
        Log::channel('wallet')->info($request);
        try {
            $domain = config('domain.wallet_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $response = $client->post('/eveningdew/deposits/postback', [
                'json' => $request
            ]);
            $responseJson = json_decode($response->getBody(), true);
            Log::channel('wallet')->info($responseJson);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            Log::channel('wallet')->error($message);
        }
        Log::channel('wallet')->info('postback end');
    }
}
