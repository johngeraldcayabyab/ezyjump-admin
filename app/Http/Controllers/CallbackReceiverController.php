<?php

namespace App\Http\Controllers;

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
}
