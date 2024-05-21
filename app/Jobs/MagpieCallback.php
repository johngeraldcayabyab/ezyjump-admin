<?php

namespace App\Jobs;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MagpieCallback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Request $request;

    public function __construct(Request $request)
    {

        $this->request = $request;
    }


    public function handle(): void
    {
        Log::channel('wallet')->info('postback start');
        $request = $this->request;
        try {
            $domain = config('domain.wallet_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $response = $client->post('/eveningdew/deposits/postback', [
                'json' => $request->all()
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
