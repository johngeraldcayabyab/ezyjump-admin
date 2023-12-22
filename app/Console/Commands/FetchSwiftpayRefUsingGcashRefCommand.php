<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchSwiftpayRefUsingGcashRefCommand extends Command
{
    protected $signature = 'app:fetch-swiftpay-ref-using-gcash-ref-command {dateFrom} {dateTo} {gcashRef} {status=EXECUTED}';
    protected $description = 'Fetch swiftpay reference number using gcash reference number';

    public function handle()
    {
        $accounts = config('swiftpay');
        foreach ($accounts as $account) {
            $ref = $this->fetchRef($account);
            if ($ref) {
                $this->info($ref);
                return;
            }
        }
    }

    public function fetchRef($account)
    {
        $username = $account['username'];
        $password = $account['password'];
        $merchantId = $account['merchant_id'];
        if (!$username || !$password || !$merchantId) {
            return false;
        }
        $dateFrom = Carbon::parse($this->argument('dateFrom'))->format('Y-m-d');
        $dateTo = Carbon::parse($this->argument('dateTo'))->format('Y-m-d');
        $status = $this->argument('status');
        $gcashRef = $this->argument('gcashRef');
        try {
            $loginResponse = Http::timeout(5)->post('https://api.merchant.live.swiftpay.ph/api/users/login', [
                'username' => $username,
                'password' => $password,
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
                return false;
            }
            $getUrl = "https://api.merchant.live.swiftpay.ph/api/payments?env=PRODUCTION&pageSize=15&merchantId=$merchantId&dateFrom=$dateFrom&dateTo=$dateTo&status=$status&phrase=$gcashRef";
            $queryResponse = Http::withHeaders([
                'X-Swiftpay-Session-Token' => $xSwiftpaySessionToken
            ])->get($getUrl);
            $queryResponseArray = json_decode($queryResponse, true);
            $results = $queryResponseArray['result'];
            if (count($results)) {
                return $results[0]['referenceNo'];
            }
        } catch (Exception $exception) {
            $this->info($exception->getMessage());
        }
        return false;
    }
}
