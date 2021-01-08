<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LineBotController extends Controller
{
    public function onEvent(Request $request)
    {
        $secret = env('LINE_BOT_CHANNEL_SECRET', '');
        $body = $request->getContent();
        $signature = base64_encode(hash_hmac('sha256', $body, $secret, true));
        $header = $request->header('X-Line-Signature');
        if ($signature === $header) {
            // TODO
        }
        return response('', 200);
    }
}
