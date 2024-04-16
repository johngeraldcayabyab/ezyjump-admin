<?php

namespace App\Http\Controllers\Gateway;

use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Http\Resources\SwiftpayOrderResource;
use App\Models\SwiftpayCallback;
use App\Models\SwiftpayOrder;
use App\Models\Tenant;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GatewaySwiftpayOrderController extends Controller
{
    public function show(SwiftpayOrder $swiftpayOrder): JsonResource
    {
        $swiftpayOrder->load('merchant.tenant.user');
        return new SwiftpayOrderResource($swiftpayOrder);
    }

    public function index(Request $request): ResourceCollection
    {
        $user = Authy::user();
        $swiftpayOrder = new SwiftpayOrder();
        if (!$user) {
            return SwiftpayOrderResource::collection($swiftpayOrder->where('id', 0)->cursorPaginate(15));
        }
        if (!$user->isAdmin()) {
            $swiftpayOrder = $swiftpayOrder->tenantId($user->getTenantIds());
        }
        $field = null;
        $value = null;
        if ($request->field === 'transaction_id') {
            $field = 'transaction_id';
            $value = $request->value;
        }
        if ($request->field === 'reference_number') {
            $field = 'reference_number';
            $value = $request->value;
        }
        if ($request->field === 'gcash_reference') {
            $field = 'gcash_reference';
            $value = $request->value;
        }
        $value = Str::replace(' ', '', $value);
        if ($field === 'gcash_reference') {
            $referenceNumber = $this->fetchSwitpayRefUsingGcashRef($request, $value);
            $swiftpayOrder = $swiftpayOrder->where('reference_number', $referenceNumber);
        } else if (strlen($value)) {
            if (Str::contains($value, ',')) {
                $value = explode(',', $value);
            }
            if (is_array($value)) {
                $swiftpayOrder = $swiftpayOrder->whereIn($field, $value);
            } else {
                $swiftpayOrder = $swiftpayOrder->where($field, $value);
            }
        }
        $status = trim($request->status);
        if ($status && $status !== 'ALL') {
            $swiftpayOrder = $swiftpayOrder->where('order_status', $status);
        }
        $swiftpayOrder = $swiftpayOrder->createdAtBetween($request->dateFrom, $request->dateTo);
        $swiftpayOrder = $swiftpayOrder
            ->select(
                'id',
                'created_at',
                'transaction_id',
                'reference_number',
                'order_status',
                'amount'
            )
            ->orderBy('id', 'desc');
        $swiftpayOrder = $swiftpayOrder->cursorPaginate(15);
        return SwiftpayOrderResource::collection($swiftpayOrder);
    }

    private function fetchSwitpayRefUsingGcashRef($request, $gcashRef)
    {
        $dateFrom = Carbon::today()->timezone('Asia/Manila')->startOfDay()->subHours(8);
        $dateTo = now()->timezone('Asia/Manila');
        if (Carbon::hasFormat($request->dateFrom, 'Y-m-d') && Carbon::hasFormat($request->dateTo, 'Y-m-d')) {
            $dateFrom = Carbon::parse($request->dateFrom)->startOfDay()->subHours(8);
            $dateTo = Carbon::parse($request->dateT)->endOfDay()->subHours(8);
        }
        $status = trim($request->status);
        if (!in_array($status, ['PENDING', 'EXECUTED', 'CANCELED', 'REJECTED', 'EXPIRED'])) {
            $status = 'EXECUTED';
        }
        Artisan::call("app:fetch-swiftpay-ref-using-gcash-ref-command", [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'gcashRef' => $gcashRef,
            'status' => $status,
        ]);
        return trim(Artisan::output());
    }


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
            $domain = config('domain.gateway_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
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
        $swiftpayOrder = SwiftpayOrder::find($id);
        if (!$swiftpayOrder) {
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
            $tenant = Tenant::where('tenant_id', $swiftpayOrder->tenant_id)->first();
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
            $domain = config('domain.gateway_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
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
            info("callback retry status " . $referenceId . " " . $retryStatus);
            return response()->json(['retry_status' => $retryStatus]);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            Log::error($message);
            return ['status' => 'error', 'message' => $message];
        }
    }
}
