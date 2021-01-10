<?php

namespace Tests\Feature\Services;

use App\Events\ReplyLineMessage;
use App\Models\Group;
use App\Services\LineBotService;
use Illuminate\Http\Client\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class LineBotTest extends TestCase
{
    use RefreshDatabase;

    public function testJoin()
    {
        Event::fake();
        $service = new LineBotService();
        $service->join([
            'replyToken' => 'test',
            'type' => 'join',
            'source' => [
                'type' => 'group',
                'groupId' => 'test'
            ]
        ]);
        $count = Group::where('group_id', 'test')->get()->count();
        assertEquals(1, $count);
        Event::assertDispatched(function (ReplyLineMessage $event)
        {
            return $event->replyToken === 'test' &&
                $event->messages[0]['text'] === '我是值日生提醒小精靈，請到這裡 -> https://duty-web.ballfish.io 設定值日生';
        });
    }

    public function testNotJoin()
    {
        Event::fake();
        $service = new LineBotService();
        $service->join([
            'replyToken' => 'test',
            'type' => 'join',
            'source' => [
                'type' => 'room',
                'groupId' => 'test'
            ]
        ]);
        $count = Group::where('group_id', 'test')->get()->count();
        assertEquals(0, $count);
        Event::assertNotDispatched(ReplyLineMessage::class);
    }

    public function testReplyMessage()
    {
        Http::fake();
        $service = new LineBotService();
        $service->replyMessage('test', [
            [
                "type" => "text",
                "text" => "test"
            ]
        ]);
        Http::assertSent(function (Request $request)
        {
            $token = env('LINE_BOT_CHANNEL_ACCESS_TOKEN', '');
            return $request->hasHeader('Authorization', 'Bearer '.$token) &&
                $request->url() === 'https://api.line.me/v2/bot/message/reply' &&
                $request->method() === 'POST' &&
                $request['replyToken'] === 'test' &&
                is_array($request['messages']);
        });
    }

    public function testCreateNotificationMessage()
    {
        $service = new LineBotService();
        $message = $service->createNotificationMessage(array('ballfish', '@botfish'));
        $this->assertEquals('本週值日生： @ballfish @botfish', $message);
    }

    public function testPushMessage()
    {
        Http::fake();
        $service = new LineBotService();
        $service->pushMessage('test', [
            [
                "type" => "text",
                "text" => "test"
            ]
        ]);
        Http::assertSent(function (Request $request)
        {
            $token = env('LINE_BOT_CHANNEL_ACCESS_TOKEN', '');
            return $request->hasHeader('Authorization', 'Bearer '.$token) &&
                $request->url() === 'https://api.line.me/v2/bot/message/push' &&
                $request->method() === 'POST' &&
                $request['to'] === 'test' &&
                is_array($request['messages']);
        });
    }
}
