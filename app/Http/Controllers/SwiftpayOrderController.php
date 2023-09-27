<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SwiftpayOrderController
{
    public function order(Request $request)
    {
        info($request->all());
        $token = env('SWIFTPAY_1_TOKEN');
        $bearerToken = "Bearer $token";
        $data = [
            'amount' => $request->amount,
            'customerDetails' => [
                'customerName' => (string)Str::uuid(),
                'country' => 'PH',
//                'email' => 'email@example.com',
//                'phone' => '09088764955',
//                'city' => 'Quezon City',
//                'state' => 'Metro Manila',
//                'postcode' => '1110',
//                'address1' => '39 Sarangaya Ave. Brgy. White Plains'
            ],
            'institutionCode' => 'GCASH',
            'callbackUrl' => 'https://redirect.me/goodstuff',
            'transactionId' => $request->transactionId
        ];
        info($data);
        try {
            $client = new Client([
                'base_uri' => 'https://api.ezyjump-pay.com'
            ]);
            $response = $client->post('/api/orders', [
                'headers' => [
                    'Authorization' => $bearerToken,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            $responseJson = json_decode($response->getBody(), true);
            info($responseJson);
            return $responseJson;
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            Log::error($message);
            return ['status' => 'error', 'message' => $message];
        }
    }
}
