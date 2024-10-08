<?php

namespace App\Http\Controllers;

use App\Jobs\MagpieCallback;
use App\Jobs\MagpieSync;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CallbackReceiverController
{
    public function receiver(Request $request)
    {
        $all = $request->all();
        info('**This is a test callback receiver**');
        info($all);
        info('**This is a test callback receiver end**');
        return response(['status' => 200, 'content' => $all]);
    }

    public function magpie(Request $request)
    {
        $client = new Client();
        $requestInputUrl = rawurldecode($request->input('url'));
        info($requestInputUrl);
        $url = $this->encodeQueryParam($requestInputUrl);
        try {
            $response = $client->request('GET', $url);
            $body = $response->getBody()->getContents();
            return response()->json(json_decode($body, true));
        } catch (\Exception $e) {
            info($e->getMessage());
            return false;
        }
    }

    public function encodeQueryParam($url)
    {
        // Separate the URL and the data part
        $urlParts = parse_url($url);
        $baseURL = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'];
        $dataParam = $urlParts['query'];
        $encodedDataParam = str_replace('%3D', '=', rawurlencode($dataParam));

        $encodedURL = $baseURL . '?' . $encodedDataParam;
        return $encodedURL;
    }

    public function postback(Request $request)
    {
        $requestAll = $request->all();
        info('original callback start');
        info($requestAll);
        info('original callback end');
        return response()->json($requestAll);
    }

    public function sync(Request $request)
    {
        $requestAll = $request->all();
        MagpieSync::dispatch($requestAll);
        return response()->json([]);
    }

    public function success(Request $request)
    {
        $requestAll = $request->all();
        $format = [
            'refno' => $requestAll['reference_number'],
            'status' => 'paid',
            'amount' => $requestAll['amount'],
            'gcstat' => 'paid',
            'gcdate' => $requestAll['charge_date'],
            'message' => 'Message',
        ];
        MagpieCallback::dispatch($format, $requestAll);
        return response()->json([]);
    }

    public function failed(Request $request)
    {
        $requestAll = $request->all();
        $format = [
            'refno' => $requestAll['reference_number'],
            'status' => $requestAll['status'],
            'amount' => $requestAll['amount'],
            'gcstat' => $requestAll['status'],
            'gcdate' => $requestAll['charge_date'],
            'message' => 'Failed',
        ];
        MagpieCallback::dispatch($format, $requestAll);
        return response()->json([]);
    }
}
