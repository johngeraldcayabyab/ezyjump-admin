<?php

namespace App\Http\Controllers\Gateway;

use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Models\SwiftpayCallback;
use App\Models\SwiftpayQueryOrder;
use App\Models\Tenant;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GatewaySwiftpayOrderController extends Controller
{
    public function order(Request $request)
    {
        info($request->all());
        $token = $request->header('Authorization');
        $token = str_replace('Bearer', '', $token);
        $token = trim($token);
        info($token);
        $bearerToken = "Bearer $token";
        $data = [
            'amount' => $request->amount,
            'customerDetails' => [
                'customerName' => (string)Str::uuid(),
                'country' => 'PH',
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
        $id = $request->id;
        info("sync id " . $id);
        $swiftpayQueryOrder = SwiftpayQueryOrder::find($id);
        if (!$swiftpayQueryOrder) {
            return ['status' => 'error', 'message' => "$id No. Does not exist"];
        }
        $token = config('tokens.EZYJUMP_TOKEN');
        $token = str_replace('Bearer', '', $token);
        $token = trim($token);
        $bearerToken = "Bearer $token";
        $user = Authy::user();
        if (!$user) {
            return ['status' => 'error', 'message' => 'Not authenticated!'];
        }
        if (!$user->isAdmin()) {
            $tenant = Tenant::where('tenant_id', $swiftpayQueryOrder->tenant_id)->first();
            if (!$tenant) {
                return ['status' => 'error', 'message' => 'Authorization token¬ does not exists'];
            }
            $token = $tenant->authorization_token;
        }
        info($token);
        $data = [
            'data' => [
                $id,
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
            info("sync status $id $syncStatus");
            return response()->json(['sync_status' => $syncStatus]);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            Log::error($message);
            return ['status' => 'error', 'message' => $message];
        }
    }

    public function retryCallback(Request $request)
    {
        $referenceId = $request->reference_id;
        $data = ['data' => $referenceId];
        $swiftpayCallback = SwiftpayCallback::where('reference_id', $referenceId)->first();
        if (!$swiftpayCallback) {
            return ['status' => 'error', 'message' => "$referenceId No. Does not exist"];
        }
        $token = config('tokens.EZYJUMP_TOKEN');
        $token = str_replace('Bearer', '', $token);
        $token = trim($token);
        $user = Authy::user();
        if (!$user) {
            return ['status' => 'error', 'message' => 'Not authenticated!'];
        }
        if (!$user->isAdmin()) {
            $tenant = Tenant::where('tenant_id', $swiftpayCallback->tenant_id)->first();
            if (!$tenant) {
                return ['status' => 'error', 'message' => 'Authorization token¬ does not exists'];
            }
            $token = $tenant->authorization_token;
        }
        info($token);
        info("retry callback ref no " . $referenceId);
        $bearerToken = "Bearer $token";
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
            info("callback retry status " . $referenceId . " " . $retryStatus);
            return response()->json(['retry_status' => $retryStatus]);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            Log::error($message);
            return ['status' => 'error', 'message' => $message];
        }
    }
}
