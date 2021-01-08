<?php

namespace Tests\Feature;

use Tests\TestCase;

class LineBotTest extends TestCase
{
    public function test_example()
    {
        $response = $this->post('/line-bot');

        $response->assertStatus(200);
    }
}
