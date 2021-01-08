<?php

namespace Tests\Feature\Hooks;

use Tests\TestCase;

class LineBotTest extends TestCase
{
    public function testOnEvent()
    {
        $response = $this->post('/line-bot');

        $response->assertStatus(200);
    }
}
