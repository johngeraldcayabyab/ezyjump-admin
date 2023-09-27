<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class SwiftpayOrderController
{
    public function order()
    {
        $url = 'https://api.ezyjump-pay.com/api/orders';
        $token = env('SWIFTPAY_1_TOKEN');
        $bearerToken = "Bearer $token";
        $data = [
            'amount' => 11.00,
            'customerDetails' => [
                'customerName' => 'testcust',
                'country' => 'PH',
                'email' => 'email@example.com',
                'phone' => '09088764955',
                'city' => 'Quezon City',
                'state' => 'Metro Manila',
                'postcode' => '1110',
                'address1' => '39 Sarangaya Ave. Brgy. White Plains'
            ],
            'institutionCode' => 'GCASH',
            'callbackUrl' => 'https://redirect.me/goodstuff',
            'transactionId' => 'test'
        ];
        $client = new Client();
        $response = $client->post($url, [
            'headers' => [
                'Authorization' => $bearerToken,
                'Content-Type' => 'application/json'
            ],
            'json' => $data
        ]);
        return $response->getBody()->getContents();
    }
}
