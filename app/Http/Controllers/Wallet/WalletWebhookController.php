<?php

namespace App\Http\Controllers\Wallet;

use App\Data\EntityTypes;
use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Http\Resources\WalletWebhookResource;
use App\Jobs\WebhookRetry;
use App\Models\WalletWebhook;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class WalletWebhookController extends Controller
{
    public function view(): View
    {
        return view('wallet.webhook');
    }

    public function index(Request $request): ResourceCollection
    {
        $user = Authy::user();
        $meta = session('user_metadata');
        $webhook = new WalletWebhook();
        if (!$user) {
            return WalletWebhookResource::collection($webhook->where('id', 0)->cursorPaginate(15));
        }
        if (!in_array('DASHBOARD_ADMIN', $meta['permissions'])) {
            $webhook = $webhook->where('merchant_id', $user->id);
        }
        $field = null;
        $value = null;
        if ($request->field === 'id') {
            $field = 'id';
            $value = $request->value;
        }
        if ($request->field === 'entity_id') {
            $field = 'entity_id';
            $value = $request->value;
        }
        $webhook = $this->getIn($webhook, $field, $value);
        $status = trim($request->status);
        if ($status && $status !== 'ALL') {
            $webhook = $webhook->where('status', $status);
        }
        if ($request->entity_type && in_array($request->entity_type, [EntityTypes::TIDUS_CASHIN, EntityTypes::ASUKA_CASHIN])) {
            $webhook = $webhook->where('entity_type', $request->entity_type);
        }
        $webhook = $webhook->createdAtBetween($request->dateFrom, $request->dateTo);
        $webhook = $webhook
            ->select(
                'id',
                'entity_id',
                'retry_count',
                'event_type',
                'status',
                'created_at',
            )
            ->orderBy('created_at', 'desc');
        $webhook = $webhook->cursorPaginate(15);
        return WalletWebhookResource::collection($webhook);
    }

    public function retry(Request $request)
    {
        $id = $request->id;
        $webhook = WalletWebhook::where('id', $id)->first();
        if (!$webhook) {
            return ['status' => 'error', 'message' => "$id does not exist"];
        }
        $session = session('user_metadata');
        $token = $session['token'];
        $token = str_replace('Bearer', '', $token);
        $token = trim($token);
        $user = Authy::user();
        $meta = session('user_metadata');
        if (!$user) {
            return ['status' => 'error', 'message' => 'Not authenticated!'];
        }
        $path = '/dashboard/admin/webhooks/retry';
        if (!in_array('DASHBOARD_ADMIN', $meta['permissions'])) {
            if (Cache::has("webhook_$id")) {
                return ['status' => 'error', 'message' => 'Retry again in 5 minutes!'];
            }
            if (!in_array('WEBHOOK_RETRY', $meta['permissions'])) {
                return ['status' => 'error', 'message' => "You don't have permission to retry!"];
            } else {
                $path = '/dashboard/webhooks/retry';
            }
        }
        WebhookRetry::dispatch($token, $id, $path);
        return ['retry_status' => 200];
    }
}
