<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReplyLineMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $replyToken;
    public array $messages;

    /**
     * 
     * @param string $replyToken 
     * @param array $messages 
     * @return void 
     */
    public function __construct(string $replyToken, array $messages)
    {
        $this->replyToken = $replyToken;
        $this->messages = $messages;
    }
}
