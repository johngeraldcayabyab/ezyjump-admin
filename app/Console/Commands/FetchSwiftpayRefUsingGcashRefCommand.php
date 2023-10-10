<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchSwiftpayRefUsingGcashRefCommand extends Command
{
    protected $signature = 'app:fetch-swiftpay-ref-using-gcash-ref-command {dateFrom} {dateTo} {gcashRef} {status=EXECUTED}';
    protected $description = 'Fetch swiftpay reference number using gcash reference number';

    public function handle()
    {

        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo');
        $status = $this->argument('status');
        $gcashRef = $this->argument('gcashRef');


        try {
            $loginResponse = Http::post('https://api.merchant.live.swiftpay.ph/api/users/login', [
                'username' => config('swiftpay.username'),
                'password' => config('swiftpay.password'),
                'termsAgreement' => true,
            ]);
            $headers = $loginResponse->headers();
            $xSwiftpaySessionToken = null;
            foreach ($headers as $key => $value) {
                if ($key === 'X-Swiftpay-Session-Token') {
                    $xSwiftpaySessionToken = $value[0];
                }
            }
            if (!$xSwiftpaySessionToken) {
                $this->info($loginResponse);
                return;
            }
            $queryResponse = Http::withHeaders([
                'X-Swiftpay-Session-Token' => $xSwiftpaySessionToken
            ])->get("https://api.merchant.live.swiftpay.ph/api/payments?env=PRODUCTION&pageSize=15&merchantId=2647&dateFrom=$dateFrom&dateTo=$dateTo&status=$status&phrase=$gcashRef");
            $queryResponseArray = json_decode($queryResponse, true);
            $this->info($queryResponseArray['result'][0]['referenceNo']);
        } catch (Exception $exception) {
            $this->info($exception);
        }
    }
}
