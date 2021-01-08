<?php

namespace App\Listeners;

use App\Events\ReplyLineMessage;
use App\Services\LineBotService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLineMessage
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

    /**
     * Handle the event.
     *
     * @param  ReplyLineMessage  $event
     * @return void
     */
    public function reply(ReplyLineMessage $event)
    {
        $this->service->replyMessage($event->replyToken, $event->messages);
    }
}
