<?php

namespace App\Http\Controllers;

use App\Services\LineBotService;
use Illuminate\Http\Request;

class LineBotController extends Controller
{
    protected LineBotService $service;

    /**
     * 
     * @param LineBotService $service 
     * @return void 
     */
    public function __construct(LineBotService $service)
    {
        $this->service = $service;
    }
    
    public function onEvent(Request $request)
    {
        $secret = env('LINE_BOT_CHANNEL_SECRET', '');
        $body = $request->getContent();
        $signature = base64_encode(hash_hmac('sha256', $body, $secret, true));
        $header = $request->header('X-Line-Signature');
        if ($signature === $header) {
            $events = $request->input('events');
            $this->service->router($events);
        }
        return response('', 200);
    }
}
