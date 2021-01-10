<?php

namespace App\Services;

use App\Events\ReplyLineMessage;
use App\Models\Group;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class LineBotService
{
    /**
     * 
     * @param array $events 
     * @return void 
     */
    public function router(array $events)
    {
        foreach ($events as $value)
        {
            switch ($value['type'])
            {
                case 'join':
                    return $this->join($value);
            }
        }
    }

    /**
     * 
     * @param array $event 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws BindingResolutionException 
     */
    public function join(array $event)
    {
        $type = $event['source']['type'];
        if ($type !== 'group') return;
        $replyToken = $event['replyToken'];
        $text = '我是值日生提醒小精靈，請到這裡 -> https://duty-web.ballfish.io 設定值日生';
        $group = new Group();
        $group->group_id = $event['source']['groupId'];
        $group->save();
        ReplyLineMessage::dispatch($replyToken, [
            [
                'type' => 'text',
                'text' => $text
            ]
        ]);
    }


    /**
     * 
     * @param array $tags 
     * @return string 
     */
    public function createNotificationMessage(array $tags)
    {
        $tags = array_map(function ($tag)
        {
            if (strpos($tag, '@') !== false) {
                return $tag;
            }
            return '@'.$tag;
        }, $tags);
        return '本週值日生： '.implode(' ', $tags);
    }

    /**
     * 
     * @param string $replyToken 
     * @param array $messages 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws Exception 
     */
    public function replyMessage(string $replyToken, array $messages)
    {
        $token = env('LINE_BOT_CHANNEL_ACCESS_TOKEN', '');
        Http::withToken($token)->post('https://api.line.me/v2/bot/message/reply', [
            'replyToken' => $replyToken,
            'messages' => $messages
        ]);
    }

    /**
     * 
     * @param string $to 
     * @param array $messages 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws Exception 
     */
    public function pushMessage(string $to, array $messages)
    {
        $token = env('LINE_BOT_CHANNEL_ACCESS_TOKEN', '');
        Http::withToken($token)->post('https://api.line.me/v2/bot/message/push', [
            'to' => $to,
            'messages' => $messages
        ]);
    }
}
