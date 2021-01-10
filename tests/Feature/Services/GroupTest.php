<?php

namespace Tests\Feature\Services;

use App\Models\Group;
use App\Repositories\GroupRepo;
use App\Services\GroupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    public function testList()
    {
        Group::factory()->create();
        $service = new GroupService(new GroupRepo());
        $result = $service->list();
        $this->assertEquals(1, $result->count());
    }
}