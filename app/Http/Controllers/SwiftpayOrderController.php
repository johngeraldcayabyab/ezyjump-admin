<?php

namespace App\Http\Controllers;

use App\Models\SwiftpayCallback;
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
        $token = $request->header('Authorization');
        $token = str_replace('Bearer', '', $token);
        $token = trim($token);
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

        if ($data['amount']) {
            $amount = (int)$data['amount'];
            if ($amount > 20000) {
                return ['status' => 'error', 'message' => 'Amount exceeded 20,000'];
            }
        }


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

    public function sync(Request $request)
    {
        info("sync id " . $request->id);
        $token = config('tokens.EZYJUMP_TOKEN');
        $token = str_replace('Bearer', '', $token);
        $token = trim($token);
        $bearerToken = "Bearer $token";
        $data = [
            'data' => [
                $request->id,
            ]
        ];
        try {
            $client = new Client([
                'base_uri' => 'https://api.ezyjump-pay.com'
            ]);
            $response = $client->put('/api/orders/sync', [
                'headers' => [
                    'Authorization' => $bearerToken,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            $syncStatus = $response->getStatusCode();
            info("sync status " . $request->id . " " . $syncStatus);
            return response()->json(['sync_status' => $syncStatus]);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            Log::error($message);
            return ['status' => 'error', 'message' => $message];
        }
    }

    public function retryCallback(Request $request)
    {
        info("retry callback ref no " . $request->reference_id);
        $token = config('tokens.EZYJUMP_TOKEN');
        $token = str_replace('Bearer', '', $token);
        $token = trim($token);
        $bearerToken = "Bearer $token";
        $data = ['data' => $request->reference_id];
        $swiftpayCallback = SwiftpayCallback::where('reference_id', $request->reference_id)->first();
        if (!$swiftpayCallback) {
            return ['status' => 'error', 'message' => "{$request->reference_id} No. Does not exist"];
        }
        if ((int)$swiftpayCallback->delivery_count >= 3) {
            return ['status' => 'error', 'message' => 'Callbacks retry have been exceeded, please contact the admin.'];
        }
        try {
            $client = new Client([
                'base_uri' => 'https://api.ezyjump-pay.com'
            ]);
            $response = $client->put('/api/callbacks/retry?status=SUCCESS', [
                'headers' => [
                    'Authorization' => $bearerToken,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            $retryStatus = $response->getStatusCode();
            info("callback retry status " . $request->id . " " . $retryStatus);
            return response()->json(['retry_status' => $retryStatus]);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            Log::error($message);
            return ['status' => 'error', 'message' => $message];
        }
    }
}
