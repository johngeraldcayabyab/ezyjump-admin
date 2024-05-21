<?php

namespace App\Http\Controllers\Wallet;

use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Http\Resources\WalletMagpieDepositResource;
use App\Models\WalletMagpieDeposit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\View\View;

class WalletMagpieDepositController extends Controller
{
    public function view(): View
    {
        return view('wallet.magpie-deposit');
    }

    public function index(Request $request): ResourceCollection
    {
        $user = Authy::user();
        $meta = session('user_metadata');
        $magpieDeposit = new WalletMagpieDeposit();
        if (!$user) {
            return WalletMagpieDepositResource::collection($magpieDeposit->where('id', 0)->cursorPaginate(15));
        }
        if (!in_array('DASHBOARD_ADMIN', $meta['permissions'])) {
            $magpieDeposit = $magpieDeposit->where('merchant_id', $user->id);
        }
        $field = null;
        $value = null;
        if ($request->field === 'transaction_id') {
            $field = 'transaction_id';
            $value = $request->value;
        }
        if ($request->field === 'order_id') {
            $field = 'order_id';
            $value = $request->value;
        }
        if ($request->field === 'gcash_reference_number') {
            $field = 'gcash_reference_number';
            $value = $request->value;
        }
        $magpieDeposit = $this->getIn($magpieDeposit, $field, $value);
        $status = trim($request->status);
        if ($status && $status !== 'ALL') {
            $magpieDeposit = $magpieDeposit->where('status', $status);
        }
        $magpieDeposit = $magpieDeposit->createdAtBetween($request->dateFrom, $request->dateTo);
        $magpieDeposit = $magpieDeposit
            ->select(
                'id',
                'created_at',
                'order_id',
                'transaction_id',
                'amount',
                'gcash_reference_number',
                'status',
            )
            ->orderBy('id', 'desc');
        $magpieDeposit = $magpieDeposit->cursorPaginate(15);
        return WalletMagpieDepositResource::collection($magpieDeposit);
    }
}
