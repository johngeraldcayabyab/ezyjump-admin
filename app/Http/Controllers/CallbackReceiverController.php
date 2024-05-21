<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
        info('postback start');
        info($request->all());
        try {
            $domain = config('domain.wallet_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $response = $client->post('/eveningdew/deposits/postback', [
                'json' => $request->all()
            ]);
            $responseJson = json_decode($response->getBody(), true);
            info($responseJson);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            info($message);
        }
        info('postback end');
        return response()->json($request->all());
    }

    public function sync(Request $request)
    {
        info('wallet sync start');
        info($request->all());
        $client = new Client();
        $url = $request->url;
        $data = $request->data;
        try {
            $fullUrl = "$url?data=$data";
            info($fullUrl);
            $response = $client->request('GET', $fullUrl);
            $body = $response->getBody()->getContents();
            info($body);
            info('wallet sync end');
            return response()->json(json_decode($body, true));
        } catch (\Exception $e) {
            info($e->getMessage());
            return false;
        }
    }
}
